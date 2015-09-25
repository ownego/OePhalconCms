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

use Phalcon\Web\Tools;
use Phalcon\Builder\BuilderException;
use Phalcon\Builder\Scaffold;

class ScaffoldController extends ControllerBase
{

    public function indexAction()
    {

        $this->listTables();
        
        $config = Tools::getConfig();
        
        $modules = array();
        foreach($config->application->controllersDir as $model => $path) {
        	$modules[$model] = $model;
        }
        $this->view->setVar('modules', $modules);
        
        $form = $config['forms'];
        $this->view->form = (array)$form;
        
        $this->view->templateEngines = array(
            'php' => 'php',
            'volt' => 'volt'
        );
    }

    /**
     * Generate Scaffold
     */
    public function generateAction()
    {

        if ($this->request->isPost()) {

            $schema = $this->request->getPost('schema', 'string');
            $tableName = $this->request->getPost('tableName', 'string');
            $version = $this->request->getPost('version', 'string');
            $templateEngine = $this->request->getPost('templateEngine');
            $force = $this->request->getPost('force', 'int');
            $genSettersGetters = $this->request->getPost('genSettersGetters', 'int');
            $module = $this->request->getPost('module', 'string');
            $form = $this->request->getPost('form', 'string');
            
            $config = Tools::getConfig();
            $controllersNamespace = $config->application->controllersNamespace[$module];
            $modelsNamespace = $config->application->modelsNamespace[$module];
            $gridsNamespace = $config->application->gridsNamespace[$module];
            $formsNamespace = $config->application->formsNamespace[$module];

            try {

                $scaffoldBuilder = new Scaffold(array(
                    'name'      => $tableName,
                    'schema'    => $schema,
                    'force'	    => $force,
                    'genSettersGetters' => $genSettersGetters,
                    'directory' => null,
                    'templatePath'      => TEMPLATE_PATH,
                    'templateEngine'    => $templateEngine,
                    'module'    => $module,
                    'form'    => $form,
                    'controllersNamespace'    => $controllersNamespace,
                    'modelsNamespace'    => $modelsNamespace,
                    'gridsNamespace'    => $gridsNamespace,
                    'formsNamespace'    => $formsNamespace,
                ));

                $scaffoldBuilder->build();

                $this->flash->success('Scaffold for table "'.$tableName.'" was generated successfully');

            } catch (BuilderException $e) {
                $this->flash->error($e->getMessage());
            }

        }

        return $this->dispatcher->forward(array(
            'controller' => 'scaffold',
            'action' => 'index'
        ));
    }

}
