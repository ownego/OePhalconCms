<?php
namespace App\Modules\Backend\Controllers;

use App\Modules\Backend\Models\Post;
use App\Modules\Backend\Grids\PostGrid;
use App\Modules\Backend\Forms\PostForm;
use App\Modules\Backend\DetailViews\PostDetailView;

class PostController extends BaseController {

    /**
     * Index action
     *     list of items
     */
    public function indexAction() {
        $this->pageTitle = $this->_("Post management");
        $grid = new PostGrid("Post");
        $grid->run();
        $this->view->setVars(array(
                'grid' => $grid
        ));
    }

    /**
     * View detail object
     */
    public function viewAction() {
        $this->pageTitle = $this->_("View Post");
        $id = (int)$this->request->get('id');
        
        if(empty($id)) {
            $this->flashSession->error($this->_('Invalid parameters'));
            return $this->redirect('index');
        }
        
        $model = new Post();
        $detailview = new PostDetailView("Post");
        $detailview->setSource($model->get($id));
        
        $this->view->setVars(array(
            'detailview' => $detailview
        ));
    }
    
    /**
     * Create new object
     */
    public function createAction() {
        $this->pageTitle = $this->_("Create Post");
        $form = new PostForm();
    
        if($this->request->isPost()) {
            if($form->isValid($_POST)) {
                $model = new Post();
                if($model->save($form->getValues())) {
                    $this->flashSession->success($this->_("Create Post successfully"));
                    return $this->redirect('index');
                } else {
                    $this->flashSession->error($this->_("Create Post error"));
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
        $this->pageTitle = $this->_("Update Post");
        
        $id = (int)$this->request->get('id');   
        if(empty($id)) {
            $this->flashSession->error($this->_("Invalid Post parameters"));
            return $this->redirect('index');
        }
    
        $model = Post::findFirst($id);    
        if(empty($model)) {
            $this->flashSession->error($this->_("Post not found"));
            return $this->redirect('index');
        }
    
        $form = new PostForm($model);
        
        if($this->request->isPost()) {
            if($form->isValid($_POST)) {
                if($model->save($form->getValues())) {
                    $this->flashSession->success($this->_("Update Post successfully"));
                    return $this->redirect('index');
                } else {
                    $this->flashSession->error($this->_("Update Post error"));
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
    
        $model = Post::findFirst($id);
        if(empty($model)) {
            $this->flashSession->error($this->_("Post not found"));
            return $this->redirect('index');
        }
        
        $model->status = Post::STATUS_DELETED;
    
        if($model->save()) {
            $this->flashSession->success($this->_('Delete successfully'));
        } else {
            $this->flashSession->error($this->_('Delete error'));
        }
    
        return $this->redirect('index');
    }
}