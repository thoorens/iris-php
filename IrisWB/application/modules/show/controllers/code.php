<?php

namespace modules\show\controllers;

/*
 * This file is part of IRIS-PHP distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2015 Jacques THOORENS
 */


/**
 * This class will provide a way to display the underlying models, controller,
 * layout and view of a IrisWB screen.
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class code extends _show {

    public function __callAction($actionName, $parameters) {
        \Iris\SysConfig\Settings::$MD5Signature = \FALSE;
        $module = substr($actionName, 0, strlen($actionName) - 6);
        $this->__module = $module;
        $this->__controller = $parameters[0];
        $this->__action = $parameters[1];
        $this->__bodyColor = 'GREEN3';
        $this->_setLayout('code');
        $this->setViewScriptName('all');

        /* @var $tabs \Dojo\views\helpers\TabContainer */
        $tabs = $this->callViewHelper('dojo_tabContainer', 'tabs');
        $tabs->setDefault('label1');
        $tabs->setDim(\NULL,950);
        $model = $this->getModel(1,$tabs, 'model', "/$module/$parameters[0]/$parameters[1]");
        if(!is_null($model)){
            $data['label1'] = $model;
        }
        //iris_debug($data);
        
    }

    public function getModel($name, $container, $containerTitle, $url) {
        $entity = \models_internal\TModels::GetEntity();
        $this->_getPart($name, \wbClasses\Part::MODEL, $container, $containerTitle, $url, $entity);
    }
    
    public function getControllerl($name, $container, $containerTitle, $url) {
        $entity = \models_internal\TControllers::GetEntity();
        $this->_getPart($name, \wbClasses\Part::MODEL, $container, $containerTitle, $url, $entity);
    }
    public function getLayout($name, $container, $containerTitle, $url) {
        $entity = \models_internal\TLayouts::GetEntity();
        $this->_getPart($name, \wbClasses\Part::MODEL, $container, $containerTitle, $url, $entity);
    }
    public function getViews($name, $container, $containerTitle, $url) {
        $entity = \models_internal\TViews::GetEntity();
        $this->_getPart($name, \wbClasses\Part::MODEL, $container, $containerTitle, $url, $entity);
    }
    
    /**
     * 
     * @param type $name
     * @param type $type
     * @param type $container
     * @param type $containerTitle
     * @param type $url
     * @param \Iris\DB\_Entity $entity
     */
    private function _getPart($name, $type, $container, $containerTitle, $url, $entity){
        $part = \wbClasses\Part::GetPart($name);
        $part->setType($type);
        $part->setContainer($container);
        $entity->where('sequence_id=',$url);
        $results = $entity->fetchAll();
        if(count($results)>0){
            $container->addItem($name,$containerTitle);
            foreach($results as $result){
                $part->addTitle($result->Title);
                $part->addContent($result->Content);
            }
        }
    } 

}
