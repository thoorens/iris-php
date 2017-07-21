<?php

namespace modules\db\controllers;


/**
 * srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of table_ent
 * 
 * @author jacques
 * @license not defined
 */
class views extends _db {

    /**
     * Repeated from StoreResults
     */

    const TEXT = 1;
    const CODE = 2;
    
    
    /**
     * Common features of all test
     */
    protected function _init() {
        $this->setViewScriptName('/customers');
        $this->_entityManager = \models\TInvoices::DefaultEntityManager();
        $this->dbState()->validateDB();
    }

    /*
     * =================================================================================================
     * ViewEntity
     */

    /**
     * A view management with a view entity name and a reflection entity name
     */
    public function namesAction(){
        $this->setViewScriptName('/create');
        // see _init for entity manager definition
//        $eCustomer = \Iris\DB\ViewEntity::GetEntity('vcustomers2', 'customers', $this->_entityManager);
//        $this->__Command = "\Iris\DB\ViewEntity::GetEntity('vcustomers2', 'customers', &#36;EM)";
//        $this->_commonWorkOnCustomers($eCustomer);die('OK');
    }

    /**
     * A view management with a view entity name and a metadata
     */
    public function metadataAction() {
        $this->setViewScriptName('/create');
//        $metadata = $this->_getObjectMetadata();        ////$this->_getSampleMetadata(self::FROM_OBJECT);
//        // see _init for entity manager definition
//        $eCustomer = \Iris\DB\ViewEntity::GetEntity('vcustomers2', $metadata, $this->_entityManager);
//        $this->__Command = "\Iris\DB\ViewEntity::GetEntity('vcustomers2', &#36;metadata, &#36;EM)";
//        $this->_commonWorkOnCustomers($eCustomer);
    }

    /*
     * ========================================================================================
     * View model classes
     */

    /**
     * Use of a model view containing a reflection entity name. The name of the view is deducted from
     * the model class name.
     */
    public function modelViewAction() {
        $this->setViewScriptName('/create');
        // see _init for entity manager definition
//        $eCustomer = \models\VVcustomers::GetEntity($this->_entityManager);
//        $this->__Command = "\models\VVcustomers::GetEntity(&#36;EM)";
//        $this->_commonWorkOnCustomers($eCustomer, \FALSE);
    }

    /**
     * Here, the view model class contains both the entity name (view name) and the
     * reflection entity name
     * 
     */
    public function explicitModelViewAction() {
        $this->setViewScriptName('/create');
//        // see _init for entity manager definition
//        $eCustomer = \models\Anything::GetEntity($this->_entityManager);
//        $this->__Command = "\models\Anything::GetEntity(&#36;EM)";
//        $this->_commonWorkOnCustomers($eCustomer, \FALSE);
    }

    /**
     * The view model class contains the entity name and an explicit metadata
     * 
     */
    public function metadataModelViewAction() {
        $this->setViewScriptName('/create');
        // see _init for entity manager definition
//        $eCustomer = \models\AnythingM::GetEntity($this->_entityManager);
//        $this->__Command = "\models\AnythingM::GetEntity(&#36;EM)";
//        $this->_commonWorkOnCustomers($eCustomer, \FALSE);
    }

    /*
     * ===========================================================================================
     * Tests
     */

    /**
     * Verifies the syntax of ViewEntity::GetEntity
     */
    public function testViewEntityAction() {
        $this->setViewScriptName('/create');
//        $this->setViewScriptName('/tests');
//        $EM = $this->_entityManager;
//        $metadata = $this->_getObjectMetadata(); //$this->_getSampleMetadata(self::FROM_OBJECT);
//
//        /* @var $results \Iris\controllers\helpers\StoreResults */
//        $results = $this->storeResults();
//        
//        $eCustomer = \Iris\DB\ViewEntity::GetEntity('vcustomers', 'customers', $EM);
//        $results->addGoodResult("\Iris\DB\ViewEntity::GetEntity('vcustomers','customers',&#36;EM)", 'OK', self::CODE);
//
//        $eCustomer = \Iris\DB\ViewEntity::GetEntity('vcustomers', $metadata, $EM);
//        $results->addGoodResult("\Iris\DB\ViewEntity::GetEntity('vcustomers',&#36;metadata,&#36;EM)", 'OK', self::CODE);
//
//        try {
//            $eCustomer = \Iris\DB\ViewEntity::GetEntity($EM);
//        }
//        catch (\Exception $exception) {
//            $results->addBadResult("\Iris\DB\ViewEntity::GetEntity(&#36;EM)", $exception->getMessage(), self::CODE);
//        }
//
//        try {
//            $eCustomer = \Iris\DB\ViewEntity::GetEntity('customers', $EM);
//        }
//        catch (\Exception $exception) {
//            $results->addBadResult("\Iris\DB\ViewEntity::GetEntity('customers', &#36;EM)", $exception->getMessage(), self::CODE);
//        }
//
//        try {
//            $eCustomer = \Iris\DB\ViewEntity::GetEntity($metadata, $EM);
//        }
//        catch (\Exception $exception) {
//            $results->addBadResult("\Iris\DB\ViewEntity::GetEntity(&#36;metadata, &#36;EM)", $exception->getMessage(), self::CODE);
//        }
//
//        $results->sendToView();
    }

    /**
     * Verifies the syntax of \models\Vxxxxxx::GetEntity
     */
    public function testViewModelAction() {
        $this->setViewScriptName('/create');
//        $this->setViewScriptName('/tests');
//        $EM = $this->_entityManager;
//        $metadata = $this->_getObjectMetadata(); //$this->_getSampleMetadata(self::FROM_OBJECT);
//
//        /* @var $results \Iris\controllers\helpers\StoreResults */
//        $results = $this->storeResults();
//
//        $eCustomer = \models\VVcustomers::GetEntity($EM);
//        $results->addGoodResult("\models\VVCustomers::GetEntity(&#36;EM)", 'OK', self::CODE);
//        $eCustomer->getEntityManager()->unregisterEntity('vcustomer');
//
//        try {
//            $eCustomer = \models\VVcustomers::GetEntity('customers', $EM);
//        }
//        catch (\Exception $exception) {
//            $results->addBadResult("\models\VVcustomers::GetEntity('customers',&#36;EM)", $exception->getMessage(), self::CODE);
//        }
//        try {
//            $eCustomer = \models\VVcustomers::GetEntity($metadata, $EM);
//        }
//        catch (\Exception $exception) {
//            $results->addBadResult("\models\VVcustomers::GetEntity(&#36;metadata,&#36;EM)", $exception->getMessage(), self::CODE);
//        }
//        try {
//            $eCustomer = \models\VVcustomers::GetEntity('customers', $metadata, $EM);
//        }
//        catch (\Exception $exception) {
//            $results->addBadResult("\models\VVcustomers::GetEntity('customers', &#36;metadata, &#36;EM)", $exception->getMessage(), self::CODE);
//        }
//        try {
//            $eCustomer = \models\VVnoMetadata::GetEntity($EM);
//            //iris_debug($eCustomer->getMetadata());
//        }
//        catch (\Exception $exception) {
//            $results->addBadResult("\models\VVnoMetadata::GetEntity(&#36;EM)", $exception->getMessage(), self::CODE);
//        }
//        $results->sendToView();
    }

    

}
