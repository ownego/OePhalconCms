<?php
namespace App\Modules\System\Controllers;

use App\Models\Role;
class PermissionController extends BaseController {
	
	public function indexAction() {
		$acl = array();
		$configs = $this->di->get('config');
		$modules = $configs->modules;
		
		foreach ($modules as $moduleName => $module) {
			if(strtolower($moduleName) == 'frontend') {
				continue;
			}
	
			$controllers = array();
			$path = $module['path'];
			$path = substr($path, 0, -10) . 'controllers';
			
			foreach (scandir($path) as $file) {
				if (strstr($file, "Controller.php") !== false) {
					
					$baseController = $path . DIRECTORY_SEPARATOR . 'BaseController.php';
					
					if(is_file($baseController)) {
						include_once $baseController;
					} 
					
					include_once $path . DIRECTORY_SEPARATOR . $file;
					
					foreach (get_declared_classes() as $class) {
						if (is_subclass_of($class, 'OE\Application\Controller')) {
							
							$actions = array();
							$className = explode("\\", $class);
							$className = $className[count($className)-1];
							$controller = substr($className, 0, strpos($className, "Controller"));
							
							if(str_replace('Controller.php', '', $file) != $controller) {
								continue;	
							}
							
							if(in_array(strtolower($controller), array('base', 'auth'))) {
								continue;
							}
							
							foreach (get_class_methods($class) as $action) {
								if (strstr($action, "Action") !== false) {
									$actions[] = substr($action, 0, strpos($action, "Action"));
								}
							}
							
							$controllers[$controller] = $actions;
						}
					}
				}
			}
			
			$acl[$moduleName] = $controllers;
		}
		
		$roleAll = Role::find(array('status' => Role::STATUS_ACTIVE, 'id != 1'));
		$roleID = $this->request->get('role_id', null, 1);
		$roleModel = Role::findFirst($roleID);
		
		if(!$roleModel) {
		    echo 'not find record';
		    exit;// redirect to system home page 
		} else {
		  $roleData = json_decode($roleModel->getRole(), true);
		}
		    
		$this->view->setVars(array(
			'acl' => $acl,
	        'roleAll' => $roleAll,
		    'roleID' => $roleID,
		    'roleName' => $roleModel->getName(),
		    'roleData' => $roleData
		));
	}
	
	public function updateAction() {
        if(isset($_POST) && $_POST){
            $role_id = $_POST['role_id'];
            $model = Role::findFirst($role_id);
            $data['role'] = json_encode($_POST['data']);
            if($model->save($data)) 
                $this->flashSession->success($this->_('Update role success'));
            else
                $this->flashSession->error($this->_("Update role don't success"));
            
            // delete acd.data file
            $aclFile = APP_PATH . "/../var/security/acl.data";
            if(unlink($aclFile)) {
            	$this->session->set('ACL_UPDATED', true);
            }
            
            //return $this->response->redirect('/system/permission/index?role_id='.$role_id);
            $this->redirect(array('index'));
        }else {
            //$this->response->redirect('/system/permission/index');
            $this->redirect(array('index'));
        }
	}
}