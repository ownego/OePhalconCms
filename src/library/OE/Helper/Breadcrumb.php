<?php
namespace OE\Helper;

use Phalcon\Tag;

class Breadcrumb extends \Phalcon\Mvc\Controller {
    
    public function renderBreadcrumb($breadcrumbs) {
        foreach ($breadcrumbs as $breadcrumb) {
            $data = $this->breadcrumb($breadcrumb, $breadcrumbs);
            if ($data)
                return $data;
        }
    }

    public function breadcrumb($curBreadcrumb, $breadcrumbs) {
        $str = '';
        $controller = $this->router->getControllerName();
        $action = $this->router->getActionName();
    
        if ($action == 'index') {
            $str .= $this->createLiTag($curBreadcrumb['url'], $curBreadcrumb['label']);
            $str .= $this->createLiTag('', $curBreadcrumb['label'], true);
    
        } else {
            $parentConfig = $this->findWidthController($curBreadcrumb['controller'], $breadcrumbs);
            $str .= $this->createLiTag($this->genLink($parentConfig), $parentConfig['label']);
            $str .= $this->createLiTag('', $curBreadcrumb['label'], true);
        }
    
        return $str;
    }
    
    /**
     * 
     * @param unknown $controller
     * @param unknown $breadcrumbs
     * @return array navigation config if existed controller config
     */
    public function findWidthController($controller, $breadcrumbs) {
        foreach($breadcrumbs as $breadcrum ) {
            if (strcmp($breadcrum['controller'], $controller) == 0) 
                return $breadcrum;
        }
        return '';
    }
    /**
     * 
     * @param string $link
     * @param string $name
     * @return string tag li
     */
    public function createLiTag($link, $name, $active=false) {
        $str = "";
        $str .= Tag::tagHtml('li', array('class'=> $active ? 'active' : ''));
        
        if ($link) 
            $str .= Tag::tagHtml('a', array('href' => $link));
        
        $str .= $name;
        
        if ($link)
            $str .= Tag::tagHtmlClose('a');
            
        return $str;
    }
    
    /**
     * 
     * @param unknown $arrParam
     * @param string $prefix
     * @param string $suffix
     * @return unknown|string
     */
    public function genLink($arrParam, $prefix='', $suffix='') {
        if (isset($arrParam['url']) && $arrParam['url'])
            return $arrParam['url'];
    
        $module = isset($arrParam['module']) ? $arrParam['module'] : '';
        $controller = isset($arrParam['controller']) ? $arrParam['controller'] : '';
        $action = isset($arrParam['action']) ? $arrParam['action'] : '';
    
        return $prefix.$module.DIRECTORY_SEPARATOR.$controller.DIRECTORY_SEPARATOR.$action.$suffix;
    }
}