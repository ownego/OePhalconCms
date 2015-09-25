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

class ModelsController extends ControllerBase
{

    public function indexAction()
    {
        $this->listTables(true);
        
        $modules = array();
        $config = Tools::getConfig();
        foreach($config->application->modelsDir as $model => $path) {
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

            $config = Tools::getConfig();
                
            $force = $this->request->getPost('force', 'int');
            $schema = $this->request->getPost('schema');
            $tableName = $this->request->getPost('tableName');
            $genSettersGetters = $this->request->getPost('genSettersGetters', 'int');
            $foreignKeys = $this->request->getPost('foreignKeys', 'int');
            $defineRelations = $this->request->getPost('defineRelations', 'int');
            $module = $this->request->getPost('module', 'string');
            
            $config = Tools::getConfig();
            $namespace = $config->application->modelsNamespace[$module];
            
            try {

                $component = '\Phalcon\Builder\Model';
                if ($tableName == 'all') {
                    $component = '\Phalcon\Builder\AllModels';
                }

                $modelBuilder = new $component(array(
                    'name'                  => $tableName,
                    'force'                 => $force,
                    'modelsDir'             => Tools::getConfig()->application->modelsDir[$module],
                    'directory'             => null,
                    'foreignKeys'           => $foreignKeys,
                    'defineRelations'       => $defineRelations,
                    'genSettersGetters'     => $genSettersGetters,
                    'namespace'             => $namespace,
                    'module'                => $module,
                ));
                
                $modelBuilder->build();
                if ($tableName == 'all') {
                    if (($n = count($modelBuilder->exist)) > 0) {
                        $mList = implode('</strong>, <strong>', $modelBuilder->exist);

                        if ($n == 1) {
                            $notice = 'Model <strong>' . $mList . '</strong> was skipped because it already exists!';
                        } else {
                            $notice = 'Models <strong>' . $mList . '</strong> were skipped because they already exists!';
                        }

                        $this->flash->notice($notice);
                    }
                }

                if ($tableName == 'all') {
                    $this->flash->success('Models were created successfully');
                } else {
                    $this->flash->success('Model "'.$tableName.'" was created successfully');
                }

            } catch (BuilderException $e) {
                $this->flash->error($e->getMessage());
            }

        }

        return $this->dispatcher->forward(array(
            'controller' => 'models',
            'action' => 'index'
        ));
    }

    public function listAction()
    {
        $modules = array();
        $config = Tools::getConfig();
        foreach($config->application->modelsDir as $module => $path) {
            $modules[$module] = $module;
        }
        $curModule = $this->request->get('module', 'string') ? $this->request->get('module', 'string') : array_shift(array_slice($modules, 0, 1));
        
        $this->view->setVar('modelsDir', Tools::getConfig()->application->modelsDir);
        $this->view->setVar('modules', $modules);
        $this->view->setVar('curModule', $curModule);
    }

    public function editAction($fileName)
    {
        $fileName = str_replace('..', '', $fileName);

        $curModule = $this->request->get('module', 'string');
        $modelsDir = Tools::getConfig()->application->modelsDir[$curModule];

        if (!file_exists($modelsDir.'/'.$fileName)) {
            $this->flash->error('Model could not be found');

            return $this->dispatcher->forward(array(
                'controller' => 'models',
                'action' => 'list'
            ));
        }

        $this->tag->setDefault('code', file_get_contents($modelsDir.'/'.$fileName));
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
            $modelsDir = Tools::getConfig()->application->modelsDir[$curModule];
            
            if (!file_exists($modelsDir.'/'.$fileName)) {
                $this->flash->error('Model could not be found');

                return $this->dispatcher->forward(array(
                    'controller' => 'models',
                    'action' => 'list'
                ));
            }

            if (!is_writable($modelsDir.'/'.$fileName)) {
                $this->flash->error('Model file does not has write access');

                return $this->dispatcher->forward(array(
                    'controller' => 'models',
                    'action' => 'list'
                ));
            }

            file_put_contents($modelsDir.'/'.$fileName, $this->request->getPost('code'));

            $this->flash->success('The model "'.$fileName.'" was saved successfully');
        }

        return $this->dispatcher->forward(array(
            'controller' => 'models',
            'action' => 'list'
        ));
    }
}
