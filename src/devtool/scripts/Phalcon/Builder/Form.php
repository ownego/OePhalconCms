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
class Form extends Component
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
    	
        $templateInit = "
    public function init() {
		%s
		
        \$groupBody = new Group('box-body', array(
		%s			
		), array('class' => 'box-body'));
		
		\$this->addGroup(\$groupBody);
		\$this->addGroup(new BoxFooter());
	}
";

        $templateCode = "<?php
        		
%s%sclass %sForm extends Form {
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
            if (!isset($config->application->formsDir[$this->_options['module']])) {
                throw new BuilderException(
                    "Builder doesn't knows where is the models directory"
                );
            }
            $formsDir = $config->application->formsDir[$this->_options['module']];
            
        } else {
            $formsDir = $this->_options['modelsDir'];
        }            
        
        $formsDir = rtrim(rtrim($formsDir, '/'), '\\') . DIRECTORY_SEPARATOR;             
        
        if ($this->isAbsolutePath($formsDir) == false) {
            $formPath = $path . DIRECTORY_SEPARATOR . $formsDir;
        } else {
            $formPath = $formsDir;
        }                           

        $methodRawCode = array();
        $className = $this->_options['className'];
        $formPath .= $className . 'Form.php';

        if (file_exists($formPath)) {
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
		$uses[] = 'use OE\Widget\Form;';
		$uses[] = 'use OE\Widget\Form\Group;';
		$uses[] = 'use OE\Widget\Form\Element\Text;';
		$uses[] = 'use OE\Widget\Form\Element\Email;';
		$uses[] = 'use OE\Widget\Form\Element\Password;';
		$uses[] = 'use OE\Widget\Form\Element\Select;';
		$uses[] = 'use OE\Widget\Form\Element\TextArea;';
		$uses[] = 'use Phalcon\Validation\Validator\PresenceOf;';
		$uses[] = 'use Phalcon\Validation\Validator\StringLength;';
		$uses[] = 'use Phalcon\Validation\Validator\Email as EmailValidator;';
		$uses[] = 'use App\Forms\Groups\BoxFooter;';
		$uses[] = 'use '. $this->_options['modelsNamespace'].'\\'. $this->_options['className'].';';
		
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
        $extends = 'Form';
        if (isset($this->_options['extends'])) {
            if (!empty($this->_options['extends'])) {
                $extends = $this->_options['extends'];
            }
        }

        /**
         * Check if there have been any excluded fields
         */
        
        $columnSearch = array();
        $columnName = array();
        
        foreach ($fields as $field) {
        	$name = $field->getName();
        	
        	if(in_array($name, array('id', 'created_at', 'updated_at', 'register_at'))) {
        		continue;
        	}
        	
			$validator = null;
        	$element = 'Text';
        	
        	if($field->getSize() >= 100) {
        		$element = 'TextArea';
        	}
        	
        	if($name == 'password') {
        		$element = 'Password';        		
        	}
        	
        	if($name == 'email') {
        		$element = 'Email';
        		if($field->isNotNull()) {
	        		$validator .= sprintf("\$%s->addValidator(new EmailValidator(array('message' => \$this->_('Email invalid'))));\n\t\t", $name);
        		}
        	} 
        	
        	if($name == 'status') {
        		$element = 'Select';
        		$validator .= sprintf("\$%s->setOptions(%s::listStatus());", $name, $this->_options['className']);
        	}
			
        	if($field->isNotNull()) {
        		$validator .= sprintf("\$%s->addValidator(new PresenceOf(array(
        		'message' => \$this->_('%s is required', array('n' => '%s'))
        )));\n\t\t", $name, '%n%', $name);
        	}
        	
        	if(in_array($element, array('Text', 'TextArea', 'Password', 'Email'))) {
        		$validator .= sprintf("\$%s->addValidator(new StringLength(array(
        		'max' => %s, 
        		'messageMaximum' => \$this->_('%s is too long. Maximum %s characters', array('n' => '%s', 'm' => %s)))
        ));\n\t\t", $name, $field->getSize(), '%n%', '%m%', $name, $field->getSize());
        	}
        	
        	$column = "
        \$%s = new %s('%s');
		\$%s->setLabel(\$this->_('%s'));
        %s				
";
        	$columnSearch[] = sprintf($column, $name, $element, $name, $name, $name, $validator);
        	$columnName[] = sprintf('$%s', $name);
       	}
       	
       	$columnSearch = implode('', $columnSearch);
       	$columnName = implode(', ', $columnName);
        
        $content = sprintf($templateInit, $columnSearch, $columnName);
        
        $str_use = '';
        if (!empty($uses)) {
            $str_use = implode(PHP_EOL, $uses) . PHP_EOL . PHP_EOL;
        }

        $code = sprintf(
            $templateCode,
            $namespace,
            $str_use,
            $className,
            $content
        );

        if (!@file_put_contents($formPath, $code)) {
                throw new BuilderException("Unable to write to '$formPath'");
        }

        if ($this->isConsole()) {
            $this->_notifySuccess('Form "' . $this->_options['name'] .'" was successfully created.');
        }
    }
}