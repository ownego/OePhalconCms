<?php
$namespace$

$useModel$
$useGrid$
$useForm$
$useDetailview$

class $className$Controller extends BaseController {

    /**
     * Index action
     * 	list of items
     */
    public function indexAction() {
    	$this->pageTitle = $this->_("$className$ management");
    	$grid = new $className$Grid("$className$");
    	$grid->run();
    	$this->view->setVars(array(
    			'grid' => $grid
    	));
    }

    /**
     * View detail object
     */
    public function viewAction() {
    	$this->pageTitle = $this->_("View $className$");
		$id = (int)$this->request->get('id');
		
		if(empty($id)) {
			$this->flashSession->error($this->_('Invalid parameters'));
			return $this->redirect('index');
		}
		
		$model = new $className$();
		$detailview = new $className$DetailView("$className$");
		$detailview->setSource($model->get($id));
		
		$this->view->setVars(array(
			'detailview' => $detailview
		));
	}
	
	/**
	 * Create new object
	 */
	public function createAction() {
		$this->pageTitle = $this->_("Create $className$");
		$form = new $className$Form();
	
		if($this->request->isPost()) {
			if($form->isValid($_POST)) {
				$model = new $className$();
				if($model->save($form->getValues())) {
					$this->flashSession->success($this->_("Create $className$ successfully"));
					return $this->redirect('index');
				} else {
					$this->flashSession->error($this->_("Create $className$ error"));
				}
			}
		}
			
		$this->view->setVars(array(
				'form' => $form
		));
	}

	/**
	 * Update object
	 */
    public function updateAction() {
    	$this->pageTitle = $this->_("Update $className$");
    	
    	$id = (int)$this->request->get('id');   
    	if(empty($id)) {
    		$this->flashSession->error($this->_("Invalid $className$ parameters"));
    		return $this->redirect('index');
    	}
    
    	$model = $className$::findFirst($id);    
    	if(empty($model)) {
    		$this->flashSession->error($this->_("$className$ not found"));
    		return $this->redirect('index');
    	}
    
    	$form = new $className$Form($model);
    	
    	if($this->request->isPost()) {
    		if($form->isValid($_POST)) {
    			if($model->save($form->getValues())) {
    				$this->flashSession->success($this->_("Update $className$ successfully"));
    				return $this->redirect('index');
    			} else {
    				$this->flashSession->error($this->_("Update $className$ error"));
    			}
    		}
    	}
    
    	$this->view->setVars(array(
    			'form' => $form
    	));
    }

    /**
     * Delete object
     */
    public function deleteAction() {
		$id = (int)$this->request->get('id');
	
		if(empty($id)) {
			$this->flashSession->error($this->_('Invalid parameters'));
			return $this->redirect('index');
		}
	
		$model = $className$::findFirst($id);
		if(empty($model)) {
			$this->flashSession->error($this->_("$className$ not found"));
			return $this->redirect('index');
		}
		
		$model->status = $className$::STATUS_DELETED;
	
		if($model->save()) {
			$this->flashSession->success($this->_('Delete successfully'));
		} else {
			$this->flashSession->error($this->_('Delete error'));
		}
	
		return $this->redirect('index');
	}
}