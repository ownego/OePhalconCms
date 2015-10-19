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
	    $roleModels = \App\Models\AclRole::find(array("status" => \App\Models\Role::STATUS_ACTIVE));
	    $data = array();
	    foreach ($roleModels as $key=>$roleModel) {
	        $index = $roleModel->getId(); 
	        $data[$index] = new Role($index, $roleModel->getName());
	    }
	    return $data;
	}
	
	/**
	 * Get mapping roles and resources
	 * 
	 * @return multitype:multitype:multitype:string
	 */
    public function getRoleResources() {
	    return \App\Models\AclRoleResource::find(array("status" => \App\Models\Role::STATUS_ACTIVE));
	}
	
    public function getResource($id) {
	    return \App\Models\AclResource::findFirst($id);
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
			'backend-auth' => array('login', 'logout'),
		);
	}
	
	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher) {
		$idAclRole = null;
		
		if(($auth = $this->session->get('auth')) && isset($auth['id_acl_role'])) {
			$idAclRole = $auth['id_acl_role'];
		}
		
		if(empty($idAclRole) && $dispatcher->getControllerName() != 'index' && $dispatcher->getActionName() != 'login') {
			return $this->response->redirect('/backend/auth/login');
		}
		
		if($idAclRole == self::ROLE_SUPERADMIN && !$this->session->get('ACL_UPDATED')) {
			return true;
		}
		
		$resource = $this->_getResource($dispatcher);
		$access = $this->_getAccess($dispatcher);
		
		// Check allow access
		$acl = $this->_getAcl();
		$allowed = $acl->isAllowed($idAclRole, $resource, $access);
		
		if ($allowed != Acl::ALLOW && $dispatcher->getControllerName() != 'auth') {
			return $this->response->redirect('/backend/auth/denied');
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
				
		foreach ($roleResources as $roleResource) {
		    $idAclRole = $roleResource->getIdAclRole();
		    $idAclResource = $roleResource->getIdAclResource();
		    
		    if(!($resource = self::getResource($idAclResource))) {
		    	continue;
		    }
		    
		    $resourceName = $resource->getName();
		    $resourceAction = $resource->getAction();
		    
		    if($this->_acl->isResource($resourceName)) {
		    	$this->_acl->addResourceAccess($resourceName, $resourceAction);
		    } else {
		        $this->_acl->addResource(new Resource($resourceName), $resourceAction);
		    }
		    
		    $this->_acl->allow($idAclRole, $resourceName, $resourceAction);
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
