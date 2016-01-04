<?php

namespace App\Modules\Backend\Controllers;

use App\Modules\Backend\Models\User;
use App\Models\Post as Post;
use App\Modules\Backend\Grids;
use App\Modules\Backend\Forms\Contact;
use App\Modules\Backend\DetailViews\PostDetailView;
use App\Components\Constant;
use App\Components\Common;
use OE\Helper;
use App\Modules\Backend\Models\Contract;
use App\Modules\Backend\Grids\UserDashboardGrid;
use App\Modules\Backend\Grids\ContractDashboardGrid;

class IndexController extends BaseController {

	/**
	 * Route action
	 */
	public function indexAction() {
	    $this->pageTitle = $this->_('Dashboard');
    }
	
	/**
	 * Change language
	 * 
	 * @return \Phalcon\Http\ResponseInterface
	 */
	public function changeLanguageAction() {
		$lang = $this->request->get('lang');
		if(!in_array($lang, array_keys(Constant::listLang()))) {
			$lang = Constant::LANG_EN;
		}
		$this->session->set('language', $lang);
		return $this->response->redirect($this->url->get(Common::backLink()));
	}
	
	/**
	 * Set left bar collapse
	 */
	public function setLeftbarCollapseAction() {
		$this->session->set('collapsed', (int)$this->request->get('collapsed'));
		$this->debugdie($this->request->get('collapsed'));
		$this->view->disable();
	}
}