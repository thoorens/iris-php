<?php

namespace modules\db\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of {CONTROLLER}
 * 
 * @author jacques
 * @license not defined
 */
class _dbCrud extends \modules\db\controllers\_db {

    private $_entityDescription = array(
        'customer' => 'M_customer',
        'invoice' => 'F_invoice',
    );

    public function __callAction($actionName, $parameters) {
        $subhelper = \Iris\Subhelpers\CrudIconManager::GetInstance();
        list($action, $entity) = explode('_', $actionName);
        $arEntity = explode('Action', $entity);
        $entity = $arEntity[0];
        $subhelper->setActionName($action);
        $subhelper->setEntity($this->_entityDescription[$entity]);
        $subhelper->setController($entity)->forceLanguage('english');
        //$presentation = $subhelper->prepare($action);
        $this->__crudFunction = "OOOOOOOooo";//$presentation['help'];
        $this->__title = ucfirst($entity) . " management";
//        }
        \Iris\DB\DataBrowser\_Crud::DispatchAction($this, $actionName, $parameters);
        $this->setViewScriptName('editall');
    }

    public function errorAction($num) {
        die("There is an error $num");
    }

}
