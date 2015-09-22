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

namespace Phalcon\Builder;

use Phalcon\Db\Column;
use Phalcon\Builder\Component;
use Phalcon\Builder\BuilderException;
use Phalcon\Script\Color;
use Phalcon\Text as Utils;

/**
 * ModelBuilderComponent
 *
 * Builder to generate models
 *
 * @category    Phalcon
 * @package    Builder
 * @subpackage  Model
 * @copyright   Copyright (c) 2011-2014 Phalcon Team (team@phalconphp.com)
 * @license    New BSD License
 */
class ModelModule extends Component
{
    /**
     * Mapa de datos escalares a objetos
     *
     * @var array
     */
    private $_typeMap = array(//'Date' => 'Date',
        //'Decimal' => 'Decimal'
    );

    public function __construct($options)
    {
        if (!isset($options['name'])) {
            throw new BuilderException("Please, specify the model name");
        }
        if (!isset($options['force'])) {
            $options['force'] = false;
        }
        if (!isset($options['className'])) {
            $options['className'] = Utils::camelize($options['name']);
        }
        if (!isset($options['fileName'])) {
            $options['fileName'] = $options['name'];
        }
        $this->_options = $options;
    }

    /**
     * Returns the associated PHP type
     *
     * @param  string $type
     * @return string
     */
    public function getPHPType($type)
    {
        switch ($type) {
            case Column::TYPE_INTEGER:
                return 'integer';
                break;
            case Column::TYPE_DECIMAL:
            case Column::TYPE_FLOAT:
                return 'double';
                break;
            case Column::TYPE_DATE:
            case Column::TYPE_VARCHAR:
            case Column::TYPE_DATETIME:
            case Column::TYPE_CHAR:
            case Column::TYPE_TEXT:
                return 'string';
                break;
            default:
                return 'string';
                break;
        }
    }

    public function build()
    {       
        $templateSearch = "
    /**
     * @return 
     */
    public function search() { 
		\$builder = \$this->getModelsManager()
		->createBuilder()
		->columns(array(%s))
		->addFrom(__CLASS__, 't')
		->where('t.status > :status:', array('status' => self::STATUS_DELETED));
		
		return \$builder;
	}
";
        
        $templateGet = "
    /**
     * @return 
     */
    public function get(\$id) { 
		\$builder = \$this->getModelsManager()
		->createBuilder()
		->columns(array(%s))
		->addFrom(__CLASS__, 't')
		->where('t.id = :id:', array('id' => \$id));
		
		return \$builder;
	}
";

        $templateCode = "<?php
        		        		
%s%sclass %s extends %s {
    
    const STATUS_ACTIVE   = 1;
	const STATUS_INACTIVE = -1;
	const STATUS_DELETED  = -2;
        		
    public static function listStatus() {
		return array(
			self::STATUS_ACTIVE => Translate::t('Active'),
			self::STATUS_INACTIVE => Translate::t('Inactive'),
			self::STATUS_DELETED => Translate::t('Deleted'),
		);
	}
	
	public static function getStatusLabel(\$status) {
		\$listStatus = self::listStatus();
		return isset(\$listStatus[\$status]) ? \$listStatus[\$status] : null;
	}    		
%s
}
";
        if (!$this->_options['name']) {
            throw new BuilderException("You must specify the table name");
        }

        $path = '';
        if (isset($this->_options['directory'])) {
            if ($this->_options['directory']) {
                $path = $this->_options['directory'] . '/';
            }
        } else {
            $path = '.';
        }
        $config = $this->_getConfig($path);
        
        if (!isset($this->_options['modelsDir'][$this->_options['module']])) {
            if (!isset($config->application->modelsDir[$this->_options['module']])) {
                throw new BuilderException(
                    "Builder doesn't knows where is the models directory"
                );
            }
            $modelsDir = $config->application->modelsDir[$this->_options['module']];
            
        } else {
            $modelsDir = $this->_options['modelsDir'];
        }            
        
        $modelsDir = rtrim(rtrim($modelsDir, '/'), '\\') . DIRECTORY_SEPARATOR;             
        
        if ($this->isAbsolutePath($modelsDir) == false) {
            $modelPath = $path . DIRECTORY_SEPARATOR . $modelsDir;
        } else {
            $modelPath = $modelsDir;
        }                                 

        $methodRawCode = array();
        $className = $this->_options['className'];
        $modelPath .= $className . '.php';

        if (file_exists($modelPath)) {
            if (!$this->_options['force']) {
                throw new BuilderException(
                    "The model file '" . $className .
                    ".php' already exists in models dir"
                );
            }
        }

        if (!isset($config->database)) {
            throw new BuilderException(
                "Database configuration cannot be loaded from your config file"
            );
        }

        if (!isset($config->database->adapter)) {
            throw new BuilderException(
                "Adapter was not found in the config. " .
                "Please specify a config variable [database][adapter]"
            );
        }

        if (isset($this->_options['namespace'])) {
            $namespace = 'namespace ' . $this->_options['namespace'] . ';'
                . PHP_EOL . PHP_EOL;
        } else {
            $namespace = '';
        }

        $adapter = $config->database->adapter;
        $this->isSupportedAdapter($adapter);

        if (isset($config->database->adapter)) {
            $adapter = $config->database->adapter;
        } else {
            $adapter = 'Mysql';
        }

        if (is_object($config->database)) {
            $configArray = $config->database->toArray();
        } else {
            $configArray = $config->database;
        }

        // An array for use statements
        $uses = array();
        $uses[] = 'use App\\Models\\'. $this->_options['className'].' as '. $this->_options['className'] .'Base;';
        $uses[] = 'use App\Helpers\Translate;';

        $adapterName = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;
        unset($configArray['adapter']);
        $db = new $adapterName($configArray);

        $initialize = array();
        if (isset($this->_options['schema'])) {
        	if ($this->_options['schema'] != $config->database->dbname) {
        		$initialize[] = sprintf(
        				$templateThis, 'setSchema', '"' . $this->_options['schema'] . '"'
        		);
        	}
        	$schema = $this->_options['schema'];
        } elseif ($adapter == 'Postgresql') {
        	$schema = 'public';
        	$initialize[] = sprintf(
        			$templateThis, 'setSchema', '"' . $this->_options['schema'] . '"'
        	);
        } else {
        	$schema = $config->database->dbname;
        }

        $table = $this->_options['name'];
        if ($db->tableExists($table, $schema)) {
            $fields = $db->describeColumns($table, $schema);
        } else {
            throw new BuilderException('Table "' . $table . '" does not exists');
        }

        /**
         * Check if there has been an extender class
         */
        $extends = $this->_options['className'] . 'Base';
        if (isset($this->_options['extends'])) {
            if (!empty($this->_options['extends'])) {
                $extends = $this->_options['extends'];
            }
        }

        /**
         * Check if there have been any excluded fields
         */
        $content = '';
        
        $columnSearch = array();
        foreach ($fields as $field) {
       		$columnSearch[] = "'t.". $field->getName()."'";
        }
        $columnSearch = implode(', ', $columnSearch);
        
        $content .= sprintf($templateSearch, $columnSearch);
        $content .= sprintf($templateGet, $columnSearch);

        $str_use = '';
        if (!empty($uses)) {
            $str_use = implode(PHP_EOL, $uses) . PHP_EOL . PHP_EOL;
        }

        $code = sprintf(
            $templateCode,
            $namespace,
            $str_use,
            $className,
            $extends,
            $content
        );

        if (!@file_put_contents($modelPath, $code)) {
                throw new BuilderException("Unable to write to '$modelPath'");
        }

        if ($this->isConsole()) {
            $this->_notifySuccess('Model "' . $this->_options['name'] .'" was successfully created.');
        }
    }
}