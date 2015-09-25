<?php

/*
  +------------------------------------------------------------------------+
  | Phalcon Developer Tools                                                |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2014 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  +------------------------------------------------------------------------+
*/

use Phalcon\Tag;
use Phalcon\Web\Tools;
use Phalcon\Builder\BuilderException;
use Phalcon\Builder\Form;

class FormsController extends ControllerBase
{

    public function indexAction()
    {
        $this->listTables(true);
        $modules = array();
        $config = Tools::getConfig();
        foreach($config->application->formsDir as $model => $path) {
            $modules[$model] = $model;
        }
        $this->view->setVar('modules', $modules);
    }

    /**
     * Generate models
     */
    public function createAction()
    {

        if ($this->request->isPost()) {

            $force = $this->request->getPost('force', 'int');
            $schema = $this->request->getPost('schema');
            $tableName = $this->request->getPost('tableName');
            $module = $this->request->getPost('module', 'string');
            
            $config = Tools::getConfig();
            $namespace = $config->application->formsNamespace[$module];
            $modelsNamespace = $config->application->modelsNamespace[$module];
            
            try {
            	
                $formsBuilder = new Form(array(
                    'name'                  => $tableName,
                    'force'                 => $force,
                    'formsDir'              => Tools::getConfig()->application->formsDir[$module],
                    'directory'             => null,
                    'namespace'             => $namespace,
                    'modelsNamespace'       => $modelsNamespace,
                    'module'                => $module,
                ));
                
                $formsBuilder->build();
                
                $this->flash->success('Form for table "'.$tableName.'" was generated successfully');

            } catch (BuilderException $e) {
                $this->flash->error($e->getMessage());
            }
        }

        return $this->dispatcher->forward(array(
            'controller' => 'forms',
            'action' => 'index'
        ));
    }

    public function listAction()
    {
        $modules = array();
        $config = Tools::getConfig();
        foreach($config->application->formsDir as $module => $path) {
            $modules[$module] = $module;
        }
        $curModule = $this->request->get('module', 'string') ? $this->request->get('module', 'string') : array_shift(array_slice($modules, 0, 1));
        
        $this->view->setVar('formsDir', Tools::getConfig()->application->formsDir);
        $this->view->setVar('modules', $modules);
        $this->view->setVar('curModule', $curModule);
    }

    public function editAction($fileName)
    {
        $fileName = str_replace('..', '', $fileName);

        $curModule = $this->request->get('module', 'string');
        $formsDir = Tools::getConfig()->application->formsDir[$curModule];

        if (!file_exists($formsDir.'/'.$fileName)) {
            $this->flash->error('Model could not be found');

            return $this->dispatcher->forward(array(
                'controller' => 'forms',
                'action' => 'list'
            ));
        }

        $this->tag->setDefault('code', file_get_contents($formsDir.'/'.$fileName));
        $this->tag->setDefault('name', $fileName);
        $this->tag->setDefault('curModule', $curModule);
        $this->view->setVar('name', $fileName);
    }

    public function saveAction()
    {

        if ($this->request->isPost()) {

            $fileName = $this->request->getPost('name', 'string');
            $fileName = str_replace('..', '', $fileName);
            
            $curModule = $this->request->get('curModule', 'string');
            $formsDir = Tools::getConfig()->application->formsDir[$curModule];
            
            if (!file_exists($formsDir.'/'.$fileName)) {
                $this->flash->error('Model could not be found');

                return $this->dispatcher->forward(array(
                    'controller' => 'forms',
                    'action' => 'list'
                ));
            }

            if (!is_writable($formsDir.'/'.$fileName)) {
                $this->flash->error('Model file does not has write access');

                return $this->dispatcher->forward(array(
                    'controller' => 'forms',
                    'action' => 'list'
                ));
            }

            file_put_contents($formsDir.'/'.$fileName, $this->request->getPost('code'));

            $this->flash->success('The model "'.$fileName.'" was saved successfully');
        }

        return $this->dispatcher->forward(array(
            'controller' => 'forms',
            'action' => 'list'
        ));
    }
}
