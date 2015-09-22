<?php

namespace OE\Helper;

use Phalcon\Tag;
use Phalcon;
use OE\Application\Controller;

class Navigation extends Controller {
    
    public function renderNavigation ($navigations) {
        $str = "";
        $str .= Tag::tagHtml('ul', array('class'=>'sidebar-menu'));
        
        $navigations = $this->checkRole($navigations);
        
        foreach ($navigations as $navigation) {
            $active = $this->checkParentActiveLink($navigation);
            
            if (isset($navigation['pages']) && is_array($navigation['pages'])) {
                $str .= $this->createLiTag($navigation, true, $active, false);
                $str .= Tag::tagHtml('ul', array('class'=>'treeview-menu'));
                
                foreach ($navigation['pages'] as $page) {
                    if(!isset($page['display']) || $page['display']== true) {
                        $active = $this->checkActiveLink($page['url'], $page['controller'], $page['action']);
                        $str .= $this->createLiTag($page, false, $active, true);
                    }
                }
                
                $str .= Tag::tagHtmlClose('ul');
                $str .= Tag::tagHtmlClose('li');
            } else {
                $str .= $this->createLiTag($navigation, false, $active, true);
            }
        }
        $str .= Tag::tagHtmlClose('ul');
        return $str;
    }
    
    /**
     * 
     * @param array $configNavigation
     * @return array navigation config
     */
    public function checkRole($configNavigation) {
        $userInfo = $this->session->get('auth');
        $aclFile = APP_PATH . "/../var/security/acl.data";
        $temp = array();
        if (!is_file($aclFile)) {
        	return $configNavigation;
        }
        $acl = unserialize(file_get_contents($aclFile));
        foreach ($configNavigation as $key=>$val) {
            if(!isset($val['pages']) || !is_array($val['pages'])){
                $controller = str_replace('-', '', $val['controller']);
                $controller = strtolower($controller);
                if($acl->isAllowed($userInfo['role'], $val['module'].'-'.$controller, $val['action'])) {
                    $temp[$key] = $val;
                }
            } elseif(isset($val['pages']) && is_array($val['pages'])) {
                $temp[$key] = $val;
                $pages = $val['pages'];
                $tempPage = array();
                foreach ($pages as $page) {
                    $controller = str_replace('-', '', $page['controller']);
                    $controller = strtolower($controller);
                    if($acl->isAllowed($userInfo['role'], $page['module'].'-'.$controller, $page['action'])) {
                        $tempPage[] = $page;
                    }
                }
                $temp[$key]['pages'] = $tempPage;
                if(count($tempPage) == 0) {
                    unset($temp[$key]);
                }
            }
        }
        return $temp;
    }

    /**
     * Dashboard
     *
     * @param array $navigations
     */
    public function renderDashboard($navigations)
    {
        $str = "";
        // List menu
        $str .= Tag::tagHtml('h3');
        $str .= $this->_('List Function');
        $str .= Tag::tagHtmlClose('h3');
        $str .= Tag::tagHtml('div', array('class' => 'row'));
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
                    $str .= Tag::tagHtmlClose('div');
                    $str .= Tag::tagHtml('div', array('class' => 'row'));
                }
                $navigation['box-class'] = $boxClass[$k%5];
                $str .= $this->createCol($navigation);
                ++$count;
            }
        }
        $str .= Tag::tagHtmlClose('div');

        return $str;
    }

    /**
     * Create menu
     *
     * @param array $arrParam
     */
    public function createCol($arrParam = array())
    {
        $str = "";
        $str .= Tag::tagHtml('div', array('class' => 'col-lg-3'));
        $str .= Tag::tagHtml('div', array('class' => 'panel panel-default box box-solid '. $arrParam['box-class']));
        $str .= Tag::tagHtml('div', array('class' => 'panel-heading box-header'));
        $str .= Tag::tagHtml('h4', array());
        $str .= isset($arrParam['pull-left']) ? Tag::tagHtml('i', array('class' => $arrParam['pull-left'])) : '';
        $str .= Tag::tagHtmlClose('i');
        $str .= isset($arrParam['label']) ? ' ' . $this->_($arrParam['label']) : '';
        $str .= Tag::tagHtmlClose('h4');
        $str .= Tag::tagHtmlClose('div');
        $str .= Tag::tagHtml('div', array('class' => 'list-group'));

        foreach ($arrParam['pages'] as $page) {
            $flag = false;
            if( !isset($page['display']) ) {
                $flag = true;
            } elseif($page['display'] != false) {
                $flag = true;
            }
            
            if ($flag == true) {
                $str .= Tag::tagHtml('a',
                        array('href' => $this->genLink($page), 'class' => 'list-group-item'));
                $str .= $this->_(isset($page['label']) ? $page['label'] : '');
                $str .= Tag::tagHtmlClose('a');
            }
        }

        $str .= Tag::tagHtmlClose('div');
        $str .= Tag::tagHtmlClose('div');
        $str .= Tag::tagHtmlClose('div');

        return $str;
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
    public function createLiTag($arrParam = array(), $treeView = false, $active = false, $close  = false) {
        $str = "";
        $liClass = "";
        $liClass .= $treeView ? 'treeview ' : '';
        $liClass .= $active == true ? 'active' : '';
        
        $str .= Tag::tagHtml('li', array('class'=> $liClass));
        
        $str .= Tag::tagHtml('a', 
                            array('href' => $this->genLink($arrParam)));
        
        $str .= isset($arrParam['pull-left']) ? Tag::tagHtml('i', array('class' => $arrParam['pull-left']) ) : '';
        $str .= Tag::tagHtmlClose('i');
        
        $str .= Tag::tagHtml('span', true);
        $str .= $this->_(isset($arrParam['label']) ? $arrParam['label'] : '');
        $str .= Tag::tagHtmlClose('span');
        if (!$treeView)
            $str .= isset($arrParam['pull-right']) ? $arrParam['pull-right'] : '';
        else 
            $str .= '<i class="fa pull-right fa-angle-left"></i>';
        
        $str .= Tag::tagHtmlClose('a'); // close a tag
        
        if ($close)
            $str .= Tag::tagHtmlClose('li') ; // close i tag
        
        return $str;
    }
    
    
    /**
     * 
     * @param array $arrParam
     * @param string $prefix
     * @param string $suffix
     * return link 
     */
    public function genLink($arrParam, $prefix = '', $suffix = '') {
        if (isset($arrParam['url']) && $arrParam['url'])
            return $this->url->get($arrParam['url']);
        
        $module = isset($arrParam['module']) ? $arrParam['module'] : '';
        $controller = isset($arrParam['controller']) ? $arrParam['controller'] : '';
        $action = isset($arrParam['action']) ? $arrParam['action'] : '';
        
        if($module == null && $controller == null && $action == null) {
        	return "##";
        }
        
        $uri = ($prefix ? $prefix : DIRECTORY_SEPARATOR) .$module.DIRECTORY_SEPARATOR.$controller.DIRECTORY_SEPARATOR.$action.$suffix;
        
        $url = $this->url->get($uri);
        
        return $url;
    }
    
    /**
     * 
     * @param string $url, url rewrited
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function checkActiveLink($url, $controller = '', $action = '') {
        if (strcmp($url, $_SERVER['REQUEST_URI']) == 0) 
            return true;        
        else {
            
            $curController = $this->router->getControllerName();
            $curAction = $this->router->getActionName();
           
            $cmpController = strcmp($curController, $controller);
            $cmpAction = strcmp($curAction, $action);
           
            if ($action)
                return $cmpController==0 && $cmpAction==0;
            else 
                return $cmpController == 0; 
        }
       
    }
    
    public function checkParentActiveLink($navigation) {
        if ($this->checkActiveLink('', $navigation['controller'], $navigation['action'])) 
            return true;
        
        if (isset($navigation['pages']) && is_array($navigation['pages'])) {
            foreach ($navigation['pages'] as $page) {
                $active = $this->checkActiveLink($page['url'], $page['controller'], $page['action']);
                if ($active == true)
                    return true;
            }
        }
        
        return false;
    }
    
}
