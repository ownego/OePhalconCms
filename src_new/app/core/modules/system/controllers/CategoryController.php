<?php
namespace App\Modules\System\Controllers;

use App\Modules\System\Models\Category;
use App\Modules\System\Grids\CategoryGrid;
use App\Modules\System\Forms\CategoryForm;
use App\Modules\System\DetailViews\CategoryDetailView;

class CategoryController extends BaseController {

    /**
     * Index action
     *     list of items
     */
    public function indexAction() {
        $this->pageTitle = $this->_("Category management");
        $grid = new CategoryGrid("Category");
        $grid->run();
        $this->view->setVars(array(
                'grid' => $grid
        ));
    }

    /**
     * View detail object
     */
    public function viewAction() {
        $this->pageTitle = $this->_("View Category");
        $id = (int)$this->request->get('id');
        
        if(empty($id)) {
            $this->flashSession->error($this->_('Invalid parameters'));
            return $this->redirect('index');
        }
        
        $model = new Category();
        $detailview = new CategoryDetailView("Category");
        $detailview->setSource($model->get($id));
        
        $this->view->setVars(array(
            'detailview' => $detailview
        ));
    }
    
    /**
     * Create new object
     */
    public function createAction() {
        $this->pageTitle = $this->_("Create Category");
        $form = new CategoryForm();
    
        if($this->request->isPost()) {
            if($form->isValid($_POST)) {
                $model = new Category();
                if($model->save($form->getValues())) {
                    $this->flashSession->success($this->_("Create Category successfully"));
                    return $this->redirect('index');
                } else {
                    $this->flashSession->error($this->_("Create Category error"));
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
        $this->pageTitle = $this->_("Update Category");
        
        $id = (int)$this->request->get('id');   
        if(empty($id)) {
            $this->flashSession->error($this->_("Invalid Category parameters"));
            return $this->redirect('index');
        }
    
        $model = Category::findFirst($id);    
        if(empty($model)) {
            $this->flashSession->error($this->_("Category not found"));
            return $this->redirect('index');
        }
    
        $form = new CategoryForm($model);
        
        if($this->request->isPost()) {
            if($form->isValid($_POST)) {
                if($model->save($form->getValues())) {
                    $this->flashSession->success($this->_("Update Category successfully"));
                    return $this->redirect('index');
                } else {
                    $this->flashSession->error($this->_("Update Category error"));
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
    
        $model = Category::findFirst($id);
        if(empty($model)) {
            $this->flashSession->error($this->_("Category not found"));
            return $this->redirect('index');
        }
        
        $model->status = Category::STATUS_DELETED;
    
        if($model->save()) {
            $this->flashSession->success($this->_('Delete successfully'));
        } else {
            $this->flashSession->error($this->_('Delete error'));
        }
    
        return $this->redirect('index');
    }
}