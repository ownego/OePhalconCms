<?php
namespace OE\Widget\Grid\PHPExcel;

use OE\Object;
use Phalcon\Forms\Element\Date;

class GridExcel extends Object {
	
	public $objPHPExcel;
	public $writerType = 'Excel2007';
	public $fileName;
	public $contentType;
	public $data;
	public $columnGroup;

	public static $ext = array(
		'xlsx' => 'Excel 2007',
		'xls' => 'Excel 2003',
		//'csv' => 'CSV',
		//'pdf' => 'PDF'
	);
	
	/**
	 * Construct
	 * @param unknown $data
	 * @param unknown $columnGroup
	 */
	public function __construct($data, $columnGroup) {
		$loader = new \Phalcon\Loader();
		$loader->registerDirs(array(APP_PATH . '/../library/OE/Plugin/PHPExcel/Classes'));
		$loader->register();
		
		$this->objPHPExcel = new \PHPExcel();
		
		$this->setData($data);
		$this->setColumnGroup($columnGroup);
	}
	
	/**
	 * Run export
	 * @param unknown $ext
	 * @return NULL
	 */
	public function run($ext) {
		if(!in_array($ext, array_keys(self::$ext))) {
			return null;
		}
		$this->{'export'.ucfirst($ext)}();
		$this->download();
	}	
	
	/**
	 * Set header
	 */
	public function setHeader() {
		$excelColumn = 'A';
		foreach ($this->columnGroup->columns as $index => $column) {
			if($header = $column->getHeaderExport()) {
				$this->objPHPExcel
					->setActiveSheetIndex(0)
					->setCellValue($excelColumn.'1', $header);
				$excelColumn++;
			}
		}
	}
	
	/**
	 * Set body content
	 */
	public function setBody() {
		if(count($this->getData()) == 0) {
			return null;
		}
		foreach ($this->getData() as $row=>$data) {
			$excelColumn = 'A';
			foreach ($this->columnGroup->columns as $column) {
				if($column->getHeaderExport()) {
					$this->objPHPExcel
						->setActiveSheetIndex(0)
						->setCellValue($excelColumn.($row+2), $column->getCellData($data, true));
					$excelColumn++;
				}
			}
		}
	}	
	
	/**
	 * Export excel data
	 * 	Apply for both xlsx, xls
	 */
	public function exporXlsData() {
		$this->objPHPExcel->setActiveSheetIndex(0);
		$this->setHeader();
		$this->setBody();		
	}
	
	/**
	 * Export xlsx format
	 */
	public function exportXlsx() {
		$this->contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		$this->fileName = time().'.xlsx';
		$this->writerType = 'Excel2007';
		$this->exporXlsData();
	}
	
	/**
	 * Export xls format
	 */
	public function exportXls() {
		$this->contentType = 'application/vnd.ms-excel';
		$this->fileName = time().'.xls';
		$this->writerType = 'Excel5';
		$this->exporXlsData();
		
		/*
		 * Fix crazy bug
		 * 	export die if count of data equal 1
		 * 	but ok when render againt 
		 */
		if(count($this->getData()) == 1) {
			$this->exporXlsData();
		}
	}
	
	public function exportCsv() {}
	
	public function exportPdf() {}
	
	/**
	 * Download file
	 */
	public function download() {
		header('Content-Type: '. $this->contentType);
		header('Content-Disposition: attachment;filename="'. $this->fileName .'"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		
		//header ('Expires: '. time()); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, $this->writerType);
		$objWriter->save('php://output');
		exit();
	}
	
	/**
	 * Get data
	 * @return unknown
	 */
	public function getData() {
		return $this->data;
	}
	
	/**
	 * Set columnGroup
	 * @param \OE\Widget\Grid\ColumnGroup $columnGroup
	 * @return \OE\Widget\Grid\PHPExcel\PHPExcel
	 */
	public function setColumnGroup($columnGroup) {
		$this->columnGroup = $columnGroup;
		return $this;
	}	
	
	/**
	 * Set data provider
	 * @param unknown $data
	 * @return \OE\Widget\Grid\PHPExcel\PHPExcel
	 */
	public function setData($data) {
		$this->data = $data;
		return $this;
	}
}