<?php

namespace App\Modules\Backend\Controllers;

use App\Modules\Backend\Models\Admin;
use Phalcon\Session\Adapter\Files as Session;
use App\Modules\Backend\Forms\LoginForm;
use App\Components\Common;

class AuthController extends BaseController {

    public function initialize() {
    	$this->view->setLayout('login');
    }

    public function loginAction() {
    	if($this->session->get('auth')) {
    	    return $this->redirect('index', 'index');
    	}    	
    	
    	$message = null;
    	$form = new LoginForm();
    	            
        if($this->request->isPost()) {
        	if($form->isValid($_POST)) {
	            $username = $form->getValue('username');
	            $password = $form->getValue('password');
	            $password = Common::hash($password);
	            $language = $form->getValue('language');
	            
	            $admin = Admin::findFirst(array(
	                "username = :username: AND password = :password: AND status = 1",
	                'bind' => array(
	                    'username' => $username,
	                    'password' => $password
	                )
	            ));
	            if($admin) {
	                $this->_registerSession($username, $admin->getIdAclRole(), $admin->getFullName(), $language);
	                return $this->redirect('index', 'index');
	            }
	            $message = $this->_('Incorrect ID or Password or the account has been deleted. Please check the account and try again');
        	}
        }
        
        $this->view->setVars(array(
        	'form' => $form,
        	'message' => $message,
        ));
    }

    /**
     * Register an authenticated $admin into session data
     *
     * @param $admin $admin
     */
    private function _registerSession($username, $idAclRole, $fullname, $language) {
        $this->session->set('auth', array(
		    'username' => $username,
        	'id_acl_role' => $idAclRole,	
        	'fullname' => $fullname,	
            'language' => $language
        ));
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function logoutAction() {
        $this->session->remove('auth');
        session_regenerate_id();
        return $this->redirect('login');
    }
    
    /**
     * Access denied page
     */
    public function deniedAction() {
    	$this->pageTitle = $this->_('Access denied');
    	$this->view->setLayout('main');
    }

    /**
     * Change password page 
     * 
     * @return \Phalcon\Http\ResponseInterface
     */
    public function changePasswordAction() {
        return $this->response->redirect('/backend/index/index');
    }
}
