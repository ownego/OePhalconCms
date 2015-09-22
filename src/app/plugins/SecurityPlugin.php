<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Security as SecurityPhalcon;

/**
 * Security
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin {

	use OE\Behaviour\DebugBehaviour, 
	OE\Behaviour\TranslationBehaviour;
	
	protected $_acl;
	
	const ROLE_SUPERADMIN = 1;
	
		
	/**
	 * Get list of roles
	 * 
	 * @return multitype:\Phalcon\Acl\Role
	 */
    public function getRoles() {
	    $roleModels = \App\Models\Role::find(array("status" => \App\Models\Role::STATUS_ACTIVE));
	    $data = array();
	    foreach ($roleModels as $key=>$roleModel) {
	        $index = $roleModel->getId(); 
	        $data[$index] = new Role($index);
	    }
	    return $data;
	}
	
	/**
	 * Get mapping roles and resources
	 * 
	 * @return multitype:multitype:multitype:string
	 */
    public function getRoleResources() {
	    $data = array();
	    $roleModels = \App\Models\Role::find(array("status" => \App\Models\Role::STATUS_ACTIVE));
	    
	    foreach ($roleModels as $key=>$roleModel) {
	        $index = $roleModel->getId();
	        $modules = json_decode($roleModel->getRole(), true);
	        foreach($modules as $moduleKey => $moduleVal) {
	            foreach ($moduleVal as $controllerKey=>$controllerVal) {
	                $data[$index][$moduleKey.'-'.strtolower($controllerKey)] = array_keys($controllerVal);
	            }
	        }
	    }
	    
	    return $data;
	}
	
	/**
	 * Get public resource
	 * 	apply for all roles
	 * 
	 * @return multitype:multitype:string
	 */
	public function getPublicResources() {
		return array(
			'frontend-error' => array('show404', 'show500'),
		);
	}
	
	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher) {
		$role = null;
		
		if(($auth = $this->session->get('auth')) && isset($auth['role'])) {
			$role = $auth['role'];
		}
		
		if(empty($role) && $dispatcher->getControllerName() != 'index' && $dispatcher->getActionName() != 'login') {
			$this->response->redirect('/backend/auth/login');
		}
		
		if($role == self::ROLE_SUPERADMIN && !$this->session->get('ACL_UPDATED')) {
			return;
		}
		
		$resource = $this->_getResource($dispatcher);
		$access = $this->_getAccess($dispatcher);
		
		// Check allow access
		$acl = $this->_getAcl();
		$allowed = $acl->isAllowed($role, $resource, $access);
		
		if ($allowed != Acl::ALLOW && $dispatcher->getControllerName() != 'auth') {
			$this->response->redirect('/backend/auth/denied');
			return false;
		}
	}
	
	/**
	 * Returns an existing or new access control list
	 *
	 * @returns AclList
	 */
	protected function _addResources() {
		$this->_acl->setDefaultAction(Acl::DENY);

		$this->_addRoles();

		$roles = $this->getRoles();
		$roleResources = $this->getRoleResources();
				
		foreach ($roleResources as $roleName => $resources) {
			foreach ($resources as $resource => $actions) {
				$this->_acl->addResource(new Resource($resource), $actions);
	
				// Grant acess to private area to roles
				foreach ($actions as $action) {
					$this->_acl->allow($roleName, $resource, $action);
				}
			}
		}

		// Public area resources
		$publicResources = $this->getPublicResources();		
		foreach ($roles as $role) {
			foreach ($publicResources as $resource => $actions) {
				$this->_acl->addResource(new Resource($resource), $actions);
				$this->_acl->allow($role->getName(), $resource, '*');
			}
		}
	}
	
	/**
	 * Get Acl object
	 *
	 * @return Ambigous <\Phalcon\Acl\Adapter\Memory, mixed>
	 */
	protected function _getAcl() {
		$aclFile = APP_PATH . "/../var/security/acl.data";
	
		if (!is_file($aclFile)) {
			$this->_acl = new \Phalcon\Acl\Adapter\Memory();
	
			$this->_addResources();
	
			// Store serialized list into plain file
			file_put_contents($aclFile, serialize($this->_acl));
	
		} else {
			//Restore acl object from serialized file
			$this->_acl = unserialize(file_get_contents($aclFile));
		}
	
		return $this->_acl;
	}
	
	/**
	 * Get current resource
	 * 
	 * @param unknown $dispatcher
	 * @return string
	 */
	protected function _getResource($dispatcher) {
	    $controllerName = str_replace('-', '', $dispatcher->getControllerName());
		return $dispatcher->getModuleName(). '-'. strtolower($controllerName);
	}
	
	/**
	 * Get current access
	 * 
	 * @param unknown $dispatcher
	 */
	protected function _getAccess($dispatcher) {
		return $dispatcher->getActionName();
	}
	
	/**
	 * Add roles to acl object
	 * 
	 * @return SecurityPlugin
	 */
	protected function _addRoles() {
		$roles = self::getRoles();
		foreach ($roles as $role) {
			$this->_acl->addRole($role);
		}
		return $this;
	}
}