<?php

namespace modules\db\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * 
 * Demonstration and test of TableEntity and model classes
 * 
 * @author jacques
 * @license not defined
 */
class tables extends _db {

    use tMetadata;
    /**
     * Repeated from StoreResults
     */
    const TEXT = 1;
    const CODE = 2;

    protected function _init() {
        $this->_entityManager = \Iris\DB\_EntityManager::EMByNumber(-2);
        $this->setViewScriptName('/customers');
        $this->dbState()->validateDB();
    }

    /*
     * --------------------------------------------------------------------------------------------
     * TableEntity
     */

    /**
     * Access to table through a table name. There is no corresponding class
     * and metadata will be found in the table
     */
    public function nameAction() {
        //i_d(\Iris\DB\_EntityManager::EMByNumber(-2));
        // see _init for entity manager definition
        $eCustomer = \Iris\DB\TableEntity::GetEntity('customers', $this->_entityManager);
        $this->__Command = "\Iris\DB\TableEntity::GetEntity('customers', &#36;EM)";
        $this->_commonWorkOnCustomers($eCustomer);
    }

    /**
     * Access to a table through its metadata. The name is found in the metadata.
     * The metadata are found in an object.
     */
    public function objectMDAction() {
        $metadata = $this->_getObjectMetadata();
        // see _init for entity manager definition
        $eCustomer = \Iris\DB\TableEntity::GetEntity($metadata, $this->_entityManager);
        $this->__Command = "\Iris\DB\TableEntity::GetEntity(&#36;metadata, &#36;EM)";
        $this->_commonWorkOnCustomers($eCustomer);
    }

    /**
     * Access to a table through its metadata. The name is found in the metadata.
     * The metadata are serialized in a string
     */
    public function serializedMDAction() {
        $metadata = $this->_getSerializedMetadata();
        // see _init for entity manager definition
        $eCustomer = \Iris\DB\TableEntity::GetEntity($metadata, $this->_entityManager);
        $this->__Command = "\Iris\DB\TableEntity::GetEntity(&#36;metadata, &#36;EM)";
        $this->_commonWorkOnCustomers($eCustomer);
    }

    /**
     * Access to a table through its metadata. The name is found in the metadata.
     * The metadata are taken from another table
     */
    public function tableMDAction() {
        $metadata = $this->_getTableMetadata($this->_entityManager);
        // see _init for entity manager definition
        $eCustomer = \Iris\DB\TableEntity::GetEntity($metadata, $this->_entityManager);
        $this->__Command = "\Iris\DB\TableEntity::GetEntity(&#36;metadata, &#36;EM)";
        $this->_commonWorkOnCustomers($eCustomer);
    }

    /*
     * ==============================================================================================
     * Model classes
     */

    /**
     * Access to table through a model class. No parameters are defined inside the model.
     */
    public function modelAction() {
        // see _init for entity manager definition
        $eCustomer = \models\TCustomers::GetEntity($this->_entityManager);
        $this->__Command = "\models\TCustomers::GetEntity(&#36;EM)";
        $this->_commonWorkOnCustomers($eCustomer);
    }

    /**
     * Access to table through an entity class providing the table name and id fields
     */
    public function explicitModelAction() {
        // see _init for entity manager definition
        $eCustomer = \models\TExplicitModel::GetEntity($this->_entityManager);
        $this->__Command = "\models\TExplicitModel::GetEntity(&#36;EM)";
        $this->_commonWorkOnCustomers($eCustomer);
    }

    /**
     * Access to table through an entity class providing the table name and id fields
     */
    public function metadataModelAction() {
        // see _init for entity manager definition
        $eCustomer = \models\TMetadataModel::GetEntity($this->_entityManager);
        $this->__Command = "\models\TMetataModel::GetEntity(&#36;EM)";
        $this->_commonWorkOnCustomers($eCustomer);
    }

    /*
     * ===========================================================================================
     * Tests
     */

    /**
     * Verifies the syntax of TableEntity::GetEntity
     */
    public function testTableEntityAction() {
        $this->setViewScriptName('/tests');
        $EM = $this->_entityManager;
        $metadata = $this->_getObjectMetadata();
        ;

        /* @var $results \Iris\controllers\helpers\StoreResults */
        $results = $this->storeResults();

        $eCustomer1 = \Iris\DB\TableEntity::GetEntity('customers', $EM);
        $results->addGoodResult("\Iris\DB\TableEntity::GetEntity('customers',&#36;EM)", 'OK', self::CODE);

        $eCustomer2 = \Iris\DB\TableEntity::GetEntity($metadata, $EM);
        $results->addGoodResult("\Iris\DB\TableEntity::GetEntity(&#36;metadata,&#36;EM)", 'OK', self::CODE);

        try {
            $eCustomer3 = \Iris\DB\TableEntity::GetEntity($EM);
        }
        catch (\Exception $exception) {
            $results->addBadResult("\Iris\DB\TableEntity::GetEntity(&#36;EM)", $exception->getMessage(), self::CODE);
        }

        try {
            $eCustomer4 = \Iris\DB\TableEntity::GetEntity('customers', $metadata, $EM);
        }
        catch (\Exception $exception) {
            $results->addBadResult("\Iris\DB\TableEntity::GetEntity('customers', &#36;metadata, &#36;EM)", $exception->getMessage(), self::CODE);
        }

        try {
            $eCustomer5 = \Iris\DB\TableEntity::GetEntity('customers', 'invoices', $EM);
        }
        catch (\Exception $exception) {
            $results->addBadResult("\Iris\DB\TableEntity::GetEntity('customers', 'invoices',&#36;EM)", $exception->getMessage(), self::CODE);
        }

        $results->sendToView();
    }

    /**
     * Verifies the syntax of \models\Txxxxxx::GetEntity
     */
    public function testModelAction() {
        $this->setViewScriptName('/tests');
        $EM = $this->_entityManager;
        $metadata = $this->_getObjectMetadata();
        ;

        /* @var $results \Iris\controllers\helpers\StoreResults */
        $results = $this->storeResults();

        $eCustomer1 = \models\TCustomers::GetEntity($EM);
        $results->addGoodResult("\models\TCustomers::GetEntity(&#36;EM)", 'OK', self::CODE);

        try {
            $eCustomer2 = \models\TCustomers::GetEntity('customers', $EM);
        }
        catch (\Exception $exception) {
            $results->addBadResult("\models\TCustomers::GetEntity('customers',&#36;EM)", $exception->getMessage(), self::CODE);
        }
        try {
            $eCustomer3 = \models\TCustomers::GetEntity($metadata, $EM);
        }
        catch (\Exception $exception) {
            $results->addBadResult("\models\TCustomers::GetEntity(&#36;metadata,&#36;EM)", $exception->getMessage(), self::CODE);
        }
        try {
            $eCustomer3 = \models\TCustomers::GetEntity('customers', $metadata, $EM);
        }
        catch (\Exception $exception) {
            $results->addBadResult("\models\TCustomers::GetEntity('customers', &#36;metadata, &#36;EM)", $exception->getMessage(), self::CODE);
        }

        try {
            $eCustomer5 = \models\TEmptyModel::GetEntity($EM);
        }
        catch (\Exception $exception) {
            $results->addBadResult("\models\TEmptyModel::GetEntity(&#36;EM)", $exception->getMessage(), self::CODE);
        }

        $results->sendToView();
    }

    /**
     * Verifies the syntax of _Entity::GetEntity. Never direct access.
     */
    public function testEntityAction() {
        $this->setViewScriptName('/tests');
        $EM = $this->_entityManager;
        $metadata = $this->_getObjectMetadata();
        ;

        /* @var $results \Iris\controllers\helpers\StoreResults */
        $results = $this->storeResults();

        try {
            $eCustomer = \Iris\DB\_Entity::GetEntity('customers', $EM);
        }
        catch (\Exception $ex) {
            $results->addBadResult("\Iris\DB\_Entity::GetEntity('customers',&#36;EM)", $ex->getMessage(), self::CODE);
        }
        try {
            $eCustomer = \Iris\DB\_Entity::GetEntity($metadata, $EM);
        }
        catch (\Exception $ex) {
            $results->addBadResult("\Iris\DB\_Entity::GetEntity(&#36;metadata,&#36;EM)", $ex->getMessage(), self::CODE);
        }
        try {
            $eCustomer = \Iris\DB\_Entity::GetEntity($EM);
        }
        catch (\Exception $ex) {
            $results->addBadResult("\Iris\DB\_Entity::GetEntity(&#36;EM)", $ex->getMessage(), self::CODE);
        }

        $results->sendToView();
    }

    public function tocAction() {
        $this->setViewScriptName('toc');
        // these parameters are only for demonstration purpose
        $this->__links = [
            'Entité axée sur le nom' => '/db/tables/name/',
            'Entité axée sur des métadata définis à la main' => '/db/tables/objectMD/',
            'Entité axée sur des métadata sérialisés' => '/db/tables/serializedMD/',
            'Entité axée sur des métadata empruntés à une table' => '/db/tables/tableMD/',
            'Entité axée sur le modèle' => '/db/tables/model/',
            'Entité axée sur un modèle explicite' => '/db/tables/explicitModel/',
            'Entité axée sur ' => '/db/tables/metadataModel/',
            'Entité axée sur une table d\'entité' => '/db/tables/testTableEntity/',
            'Entité axée sur un modèle de test' => '/db/tables/testModel/',
            'Usage interdit avec _Entity' => '/db/tables/testEntity/'
        ];
    }

    



}
