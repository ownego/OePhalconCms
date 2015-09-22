<?php

namespace App\Modules\Backend\Grids;

use App\Modules\Backend\Models;
use OE\Widget\Grid;
use OE\Widget\Grid\Filter\Date;
use Phalcon;
use OE\Widget\Grid\Filter\Text;
use OE\Widget\Grid\Filter\DateRange;
use Phalcon\Tag;
use App\Models\CmsCategory;
use OE\Widget\Grid\Filter\Select;
use App\Modules\Backend\Models\CmsPost;
use App\Modules\Backend\Grids\Elements\ActionLink;

class PostGrid extends Grid {
	
	public function init() {
		// Set data provider
		$model = new CmsPost();
		$this->setSource($model->search());
		
		// Set columns
		$this->setColumns(self::getColumns());
	}
	
	public static function getColumns() {
		return array(
			array(
				'header' => function() {
					return Phalcon\Tag::checkField(array('checkall', 'class' => 'checkall simple'));			
				},
				'value' => function ($data) {
					return Phalcon\Tag::checkField(array('post[pid][]', 'value' => $data->pid, 'class' => 'check-item simple'));
				},
				'filter' => false, 	
				'export' => false, 	
				'sort' => false, 	
				'htmlOptions' => array('class' => 'text-center'), 		
				'headerHtmlOptions' => array('class' => 'text-center'), 		
			),
			array(
				'name' => 'p.id',	
				'header' => 'PID',
				'value' => 'pid',
				'operator' => 'like',
				//'filter' => new Text(array('class' => 'abcd')),
				//'filter' => false, 	
				//'sort' => false, 	
				'htmlOptions' => array('class' => 'text-center'), 		
				'headerHtmlOptions' => array('class' => 'text-center'), 		
			),
			array(
				'name' => 'c.id',	
				'header' => 'CID',
				'value' => 'cid',
				'operator' => '=',
				'filter' => new Select(array(CmsCategory::find(), 'using' => array('id', 'title'))),	
				'htmlOptions' => array('class' => 'text-center'), 		
				'headerHtmlOptions' => array('class' => 'text-center'), 		
			),
			array(
				'name' => 'p.title',	
				'header' => 'Title',
				'value' => 'title',
				'operator' => '=',
				'htmlOptions' => array('class' => 'text-center'), 		
				'headerHtmlOptions' => array('class' => 'text-center'), 		
			),
			array(
				'name' => 'p.quote',	
				'header' => 'Quote',
				'sort' => false,
				'value'  => function ($data) {
					return substr($data->quote, 0, 100). '...';
				},
				'htmlOptions' => array('class' => 'text-center'), 		
				'headerHtmlOptions' => array('class' => 'text-center'), 		
			),
			array(
				'name' => 'p.created_date',	
				'header' => 'Created date',
				'value'  => function ($data) {
					return date('Y-m-d', strtotime($data->created_date));
				},
				'filter' => new Date(array('format' => 'yy-mm-dd')),
				'htmlOptions' => array('class' => 'text-center'), 		
				'headerHtmlOptions' => array('class' => 'text-center'), 		
			),
			array(
				'name' => 'p.updated_date',	
				'header' => 'Updated date',
				'value'  => function ($data) {
					return date('Y-m-d', strtotime($data->updated_date));
				},
				'filter' => new DateRange(array('format' => 'yy-mm-dd')),
				'htmlOptions' => array('class' => 'text-center'), 		
				'headerHtmlOptions' => array('class' => 'text-center'), 		
			),
			array(
				'name' => 'p.status',
				'header' => 'Status',
				'sort' => false,
				'value' => function ($data) {
					$label = $data->status == 1 ? 'Active' : 'Inactive';
					$class = $data->status == 1 ? 'success' : 'info';
					return sprintf('<span class="label label-%s">%s</span>', $class, $label);
				},
				'valueExport' => function ($data) {
					return $data->status == 1 ? 'Active' : 'Inactive';
				},
				'filter' => array(0 => 'Inactive', 1 => 'Active'),
				'htmlOptions' => array('class' => 'text-center'), 		
				'headerHtmlOptions' => array('class' => 'text-center'), 		
			),
			array(
				'name' => '',
				'header' => _('Action'),
				'sort' => false,
				'filter' => false,
				'export' => false,
				'value' => function($data) {
					$data->id = $data->pid;
					$actionLinks = new ActionLink($data);
					return $actionLinks->getLinks();
				},					
				'htmlOptions' => array('class' => 'text-center'), 		
				'headerHtmlOptions' => array('class' => 'text-center'), 		
			)
		);
	}
}