<?php
namespace App\Modules\Backend\Controllers;

use App\Modules\Backend\Grids\PostGrid;
use App\Modules\Backend\DetailViews\PostDetailView;
use App\Modules\Backend\Models\CmsPost;
use App\Modules\Backend\Forms\CmsPostForm;
use App\Modules\Backend\Models\CmsCategory;
use App\Models\Robots;
use App\Helpers\Translate;
class DemoController extends BaseController {
	
	public function indexAction() {
		$grid = new PostGrid('post');
		$grid->run();
		
		echo Translate::t('user_namesd');
		
		$this->_('alasdas lkfjsldk_sldkfsd');
		
// 		$post = CmsPost::findFirst(1);
// 		$this->debugdie($post->CmsCategory);
		
		
// 		$model = CmsCategory::findFirst(1);
		
// 		$this->debugdie($model->getCmsPost());

		
// 		$robot = Robots::findFirst();
// 		$robotsParts = $robot->robotsParts[0]->id;
		
// 		$this->debugdie($robotsParts);
		
		$this->view->setVars(array(
			'grid' => $grid
		));
	}
	
	public function modelAction() {
		$category = new Category();
		$category->setTitle('This is first demo model');
		$category->setDecscription('aloha');
		//$category->save();
	
	
		$post = new CmsPost();
		$post->setTitle('This is post title');
		$post->setCategoryId($category->getId());
		$post->setContent('abcd');
		//$post->save();
	
		// Model with Criteria
		$criteria = new \Phalcon\Mvc\Model\Criteria();
		$criteria->setModelName('App\Models\CmsPost')
		->columns(array('c.*'))
		->join('App\Models\CmsCategory', null, 'c')
		->notInWhere('App\Models\CmsPost.id', array(1, 2, 3, 4))
		->orderBy('App\Models\CmsPost.id DESC');
	
		// $data = $criteria->execute()->toArray();
		// $this->debug($data);
	
	
		//@return Model\Query\Builder
		$query = $this->modelsManager
		->createBuilder()
		->columns(['count(p.id) as data_count_total'])
		->addFrom('App\Models\CmsPost', 'p')
		->join('App\Models\CmsCategory', null, 'c')
		->where('p.id >= :pid:', ['pid' => 1])
		->andWhere('c.id >= :cid:', ['cid' => 1])
		->orderBy('p.id')
		->getQuery()
		->execute();
	
		// 	foreach ($query as $q) {
		// 		$this->debug($q);
		// 	}
	
	
		//@return Model\Resultset\Simple
		$post = CmsPost::find([
				'conditions' => ['id = :id:'],
				'bind' => ['id' => 1],
				'select' => ['App\Models\CmsPost.*'],
				'with' => ['App\Models\CmsCategory'],
				]);
	
		// foreach ($post as $p) {
		//	$this->debug($p->toArray());
		// }
	
	
		// Flash message
		$flash = new Phalcon\Flash\Session();
		$flash->setDI($this->di);
		$flash->error('This is flash error message');
	
	
		// Translate
		//echo $this->_('Aloha');
	
	
		// Set layout
		//$this->view->setLayout('demo');
	}
	
	public function viewAction() {
		$id = (int)$this->request->get('id');
		
		if(empty($id)) {
			$this->flashSession->error($this->_('Invalid parameters'));
			return $this->redirect('index');
		}
		
		$model = new CmsPost();
		$detailview = new PostDetailView('post');
		$detailview->setSource($model->get($id));
		
		$this->view->setVars(array(
			'detailview' => $detailview
		));
	}
	
	public function createAction() {
		$form = new CmsPostForm();
		
		$this->debugdie($form->getElements());
	
		if($this->request->isPost()) {
			if($form->isValid($_POST)) {
				$model = new CmsPost();
				if($model->save($_CmsPost)) {
					$this->flashSession->success($this->_('Create successfully'));
					return $this->redirect('index');
				} else {
					$this->flashSession->error($this->_('Create error'));
				}
			}
		}
		 
		$this->view->setVars(array(
				'form' => $form
		));
	}
	
	public function updateAction() {
		$id = (int)$this->request->get('id');
	
		if(empty($id)) {
			$this->flashSession->error($this->_('Invalid parameters'));
			return $this->redirect('index');
		}
	
		$model = CmsPost::findFirst($id);
		
		if(empty($model)) {
			$this->flashSession->error($this->_('User not found'));
			return $this->redirect('index');
		}
	
		$form = new CmsPostForm($model);
	
		if($this->request->isPost()) {
			if($form->isValid($_POST)) {
				if($model->save($form->getValues())) {
					$this->flashSession->success($this->_('Update successfully'));
					return $this->redirect('index');
				} else {
					$this->flashSession->error($this->_('Update error'));
				}
			}
		}
	
		$this->view->setVars(array(
				'form' => $form
		));
	}
	
	public function deleteAction() {
		$id = (int)$this->request->get('id');
	
		if(empty($id)) {
			$this->flashSession->error($this->_('Invalid parameters'));
			return $this->redirect('index');
		}
	
		$model = CmsPost::findFirst($id);
		$model->status = CmsPost::STATUS_DELETED;
	
		if($model->save()) {
			$this->flashSession->success($this->_('Delete successfully'));
		} else {
			$this->flashSession->error($this->_('Delete error'));
		}
	
		return $this->redirect('index');
	}
}