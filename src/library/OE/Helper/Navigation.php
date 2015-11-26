<?php

namespace OE\Helper;

use Phalcon\Tag;
use Phalcon;
use OE\Object;

class Navigation extends Object 
{
	
	public $navigations;
	
	public $router;
	
	/**
	 * Construct navigation 
	 * 
	 * @param string $moduleName
	 * @throws \Exception
	 */
	public function __construct($moduleName, $router) 
	{
		parent::__construct();
		$navigationFile = APP_PATH . '/config/'. APP_ENV .'/navigation.php';
		if(!file_exists($navigationFile)) {
			throw new \Exception("Navigation file '$navigationFile' is not exists.");
		}	
		$navigation = require $navigationFile;
		$this->navigations = $navigation[$moduleName];
		$this->router = $router;
	}
    
	/**
	 * Render navigation to html
	 * 
	 * @return unknown
	 */
    public function render($return=false) 
    {
        $html = Tag::tagHtml('ul', array('class'=>'sidebar-menu'));
        
        $navigations = $this->getNavigationByRole();
        
        foreach ($navigations as $navigation) {
            $active = $this->isParentActive($navigation);
            
            if (isset($navigation['pages']) && is_array($navigation['pages'])) {
                $html .= $this->createLiTag($navigation, true, $active, false);
                $html .= Tag::tagHtml('ul', array('class'=>'treeview-menu'));
                
                foreach ($navigation['pages'] as $page) {
                    if(!isset($page['display']) || $page['display']== true) {
                        $active = $this->isActive($page['url'], $page['controller'], $page['action']);
                        $html .= $this->createLiTag($page, false, $active, true);
                    }
                }
                
                $html .= Tag::tagHtmlClose('ul');
                $html .= Tag::tagHtmlClose('li');
            } else {
                $html .= $this->createLiTag($navigation, false, $active, true);
            }
        }
        $html .= Tag::tagHtmlClose('ul');
        
        if($return) return $html;
        echo $html;
    }
    
    /**
     * Get navigation by role
     * 
     * @return array navigation
     */
    public function getNavigationByRole() 
    {
        $navigation = array();
        
        $auth = $this->getDI()->get('session')->get('auth');
        $acl = $this->getAcl();
        if (!$acl) {
        	return $this->navigations;
        }
        
        foreach ($this->navigations as $key=>$val) {
            if(!isset($val['pages']) || !is_array($val['pages'])) {
            	
                $controller = str_replace('-', '', $val['controller']);
                $controller = strtolower($controller);
                if($acl->isAllowed($auth['id_acl_role'], $val['module'].'-'.$controller, $val['action'])) {
                    $navigation[$key] = $val;
                }
            } elseif(isset($val['pages']) && is_array($val['pages'])) {
                $navigation[$key] = $val;
                $pages = $val['pages'];
                $tempPage = array();
                foreach ($pages as $page) {
                    $controller = str_replace('-', '', $page['controller']);
                    $controller = strtolower($controller);
                    if($acl->isAllowed($auth['id_acl_role'], $page['module'].'-'.$controller, $page['action'])) {
                        $tempPage[] = $page;
                    }
                }
                $navigation[$key]['pages'] = $tempPage;
                if(count($tempPage) == 0) {
                    unset($navigation[$key]);
                }
            }
        }
        
        return $navigation;
    }

    /**
     * Dashboard
     *
     * @param array $navigations
     */
    public function renderDashboard($navigations) 
    {
        $html = Tag::tagHtml('h3');
        $html .= $this->_('List Function');
        $html .= Tag::tagHtmlClose('h3');
        $html .= Tag::tagHtml('div', array('class' => 'row'));
        $count = 0;
        $boxClass = array(
        	'box-primary',
        	'box-info',
        	'box-danger',
        	'box-success',
        	'box-warning',
        );
        foreach ($navigations as $k => $navigation) {
            if (isset($navigation['pages']) && is_array($navigation['pages'])) {
                if ($count % 4 == 0) {
                    $html .= Tag::tagHtmlClose('div');
                    $html .= Tag::tagHtml('div', array('class' => 'row'));
                }
                $navigation['box-class'] = $boxClass[$k%5];
                $html .= $this->createCol($navigation);
                ++$count;
            }
        }
        $html .= Tag::tagHtmlClose('div');

        return $html;
    }

    /**
     * Create menu
     *
     * @param array $arrParam
     */
    public function createCol($arrParam) 
    {
        $html = Tag::tagHtml('div', array('class' => 'col-lg-3'));
        $html .= Tag::tagHtml('div', array('class' => 'panel panel-default box box-solid '. $arrParam['box-class']));
        $html .= Tag::tagHtml('div', array('class' => 'panel-heading box-header'));
        $html .= Tag::tagHtml('h4', array());
        $html .= isset($arrParam['pull-left']) ? Tag::tagHtml('i', array('class' => $arrParam['pull-left'])) : '';
        $html .= Tag::tagHtmlClose('i');
        $html .= isset($arrParam['label']) ? ' ' . $this->_($arrParam['label']) : '';
        $html .= Tag::tagHtmlClose('h4');
        $html .= Tag::tagHtmlClose('div');
        $html .= Tag::tagHtml('div', array('class' => 'list-group'));

        foreach ($arrParam['pages'] as $page) {
            $flag = false;
            if( !isset($page['display']) ) {
                $flag = true;
            } elseif($page['display'] != false) {
                $flag = true;
            }
            
            if ($flag == true) {
                $html .= Tag::tagHtml('a',
                        array('href' => $this->getUrl($page), 'class' => 'list-group-item'));
                $html .= $this->_(isset($page['label']) ? $page['label'] : '');
                $html .= Tag::tagHtmlClose('a');
            }
        }

        $html .= Tag::tagHtmlClose('div');
        $html .= Tag::tagHtmlClose('div');
        $html .= Tag::tagHtmlClose('div');

        return $html;
    }

    /**
     * Create li tag
     * 
     * @param array $arrParam
     * @param string $link
     * @param string $active
     * @param boolean $close
     * @return string
     */
    public function createLiTag($arrParam = array(), $treeView = false, $active = false, $close  = false) 
    {
        $liClass = $treeView ? 'treeview ' : '';
        $liClass .= $active == true ? 'active' : '';
        
        $html = Tag::tagHtml('li', array('class'=> $liClass));
        $html .= Tag::tagHtml('a', array('href' => $this->getUrl($arrParam)));
        
        $html .= isset($arrParam['pull-left']) ? Tag::tagHtml('i', array('class' => $arrParam['pull-left']) ) : '';
        $html .= Tag::tagHtmlClose('i');
        
        $html .= Tag::tagHtml('span', true);
        $html .= $this->_(isset($arrParam['label']) ? $arrParam['label'] : '');
        $html .= Tag::tagHtmlClose('span');
        if (!$treeView)
            $html .= isset($arrParam['pull-right']) ? $arrParam['pull-right'] : '';
        else 
            $html .= '<i class="fa pull-right fa-angle-left"></i>';
        
        $html .= Tag::tagHtmlClose('a');
        
        if ($close) $html .= Tag::tagHtmlClose('li');
        
        return $html;
    }
    
    
    /**
     * Get url of page
     * 
     * @param array $arrParam
     * @param string $prefix
     * @param string $suffix
     * return link 
     */
    public function getUrl($page, $prefix=null, $suffix=null) 
    {
        if (isset($page['url']) && $page['url'])
            return $this->url->get($page['url']);
        
        $module = isset($page['module']) ? $page['module'] : '';
        $controller = isset($page['controller']) ? $page['controller'] : '';
        $action = isset($page['action']) ? $page['action'] : '';
        
        if($module == null && $controller == null && $action == null) {
        	return "##";
        }
        
        $uri = ($prefix ? $prefix : DIRECTORY_SEPARATOR) 
        .$module.DIRECTORY_SEPARATOR
        .$controller.DIRECTORY_SEPARATOR
        .$action.$suffix;
        
        return $this->getDI()->get('url')->get($uri);
    }
    
    /**
     * 
     * @param string $url, url rewrited
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isActive($url, $controller=null, $action=null) 
    {
        if (strcmp($url, $_SERVER['REQUEST_URI']) == 0) {
            return true;        	
        } else {
            $curController = $this->router->getControllerName();
            $curAction = $this->router->getActionName();
           
            $cmpController = strcmp($curController, $controller);
            $cmpAction = strcmp($curAction, $action);
           
            if ($action) {
                return $cmpController == 0 && $cmpAction == 0;            	
            } else {
                return $cmpController == 0;             	
            }
        }       
    }
    
    /**
     * Check parent of current page active
     * 
     * @param array $navigation
     * @return boolean
     */
    public function isParentActive($navigation) 
    {
        if ($this->isActive('', $navigation['controller'], $navigation['action'])) 
            return true;
        
        if (isset($navigation['pages']) && is_array($navigation['pages'])) {
            foreach ($navigation['pages'] as $page) {
                if ($this->isActive($page['url'], $page['controller'], $page['action'])) {
	                return true;
                } 
            }
        }
        
        return false;
    }
    
    /**
     * Get acl by acl file 
     * 
     * @return boolean|mixed
     */
    public function getAcl() 
    {
    	$aclFile = $this->getDI()->get('config')->security->aclFile;
    	if (!is_file($aclFile)) {
    		return false;
    	}
    	return unserialize(file_get_contents($aclFile));
    }
    
    /**
     * Get router
     * 
     * @return $router 
     */
    public function getRouter() 
    {
    	if (!$this->router) {
    		$this->router = $this->getDI()->get('router');
    	}
    	return $this->router;
    }
}
