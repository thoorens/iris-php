<?php

namespace modules\db\controllers;

/*
 * This file is part of IRIS-PHP.
 *
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IRIS-PHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IRIS-PHP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright 2011-2013 Jacques THOORENS
 *
 * 
 */

/**
 * 
 * Demonstration and test of TableEntity and model classes
 * 
 * @author jacques
 * @license not defined
 */
class tables extends _db {
    /**
     * Repeated from StoreResults
     */

    const TEXT = 1;
    const CODE = 2;

    protected function _init() {
        $this->_entityManager = \models\_invoiceEntity::DefaultEntityManager();
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
        // see _init for entity manager definition
        $eCustomer = \Iris\DB\TableEntity::GetEntity('customers2', $this->_entityManager);
        $this->__Command = "\Iris\DB\TableEntity::GetEntity('customers2', &#36;EM)";
        $this->_commonWorkOnCustomers($eCustomer);
    }

    /**
     * Access to a table through its metadata. The name is found in the metadata.
     * The metadata are found in an object.
     */
    public function metadataAction() {
        $metadata = $this->_getSampleMetadata(self::FROM_OBJECT);
        // see _init for entity manager definition
        $eCustomer = \Iris\DB\TableEntity::GetEntity($metadata, $this->_entityManager);
        $this->__Command = "\Iris\DB\TableEntity::GetEntity(&#36;metadata, &#36;EM)";
        $this->_commonWorkOnCustomers($eCustomer);
    }

    /**
     * Access to a table through its metadata. The name is found in the metadata.
     * The metadata are serialized in a string
     */
    public function metadata2Action() {
        $metadata = $this->_getSampleMetadata(self::FROM_STRING);
        // see _init for entity manager definition
        $eCustomer = \Iris\DB\TableEntity::GetEntity($metadata, $this->_entityManager);
        $this->__Command = "\Iris\DB\TableEntity::GetEntity(&#36;metadata, &#36;EM)";
        $this->_commonWorkOnCustomers($eCustomer);
    }

    /**
     * Access to a table through its metadata. The name is found in the metadata.
     * The metadata are taken from another table
     */
    public function metadata3Action() {
        $metadata = $this->_getSampleMetadata(self::FROM_TABLE);
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
        $metadata = $this->_getSampleMetadata(self::FROM_OBJECT);

        /* @var $results \Iris\controllers\helpers\StoreResults */
        $results = $this->storeResults();

        $eCustomer = \Iris\DB\TableEntity::GetEntity('customers', $EM);
        $results->addGoodResult("\Iris\DB\TableEntity::GetEntity('customers',&#36;EM)", 'OK', self::CODE);

        $eCustomer = \Iris\DB\TableEntity::GetEntity($metadata, $EM);
        $results->addGoodResult("\Iris\DB\TableEntity::GetEntity(&#36;metadata,&#36;EM)", 'OK', self::CODE);

        try {
            $eCustomer = \Iris\DB\TableEntity::GetEntity($EM);
        }
        catch (\Exception $exception) {
            $results->addBadResult("\Iris\DB\TableEntity::GetEntity(&#36;EM)", $exception->getMessage(), self::CODE);
        }

        try {
            $eCustomer = \Iris\DB\TableEntity::GetEntity('customers', $metadata, $EM);
        }
        catch (\Exception $exception) {
            $results->addBadResult("\Iris\DB\TableEntity::GetEntity('customers', &#36;metadata, &#36;EM)", $exception->getMessage(), self::CODE);
        }

        try {
            $eCustomer = \Iris\DB\TableEntity::GetEntity('customers', 'invoices', $EM);
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
        $metadata = $this->_getSampleMetadata(self::FROM_OBJECT);
        
        /* @var $results \Iris\controllers\helpers\StoreResults */
        $results = $this->storeResults();

        $eCustomer = \models\TCustomers::GetEntity($EM);
        $results->addGoodResult("\models\TCustomers::GetEntity(&#36;EM)", 'OK', self::CODE);

        try {
            $eCustomer = \models\TCustomers::GetEntity('customers', $EM);
        }
        catch (\Exception $exception) {
            $results->addBadResult("\models\TCustomers::GetEntity('customers',&#36;EM)", $exception->getMessage(), self::CODE);
        }
        try {
            $eCustomer = \models\TCustomers::GetEntity($metadata, $EM);
        }
        catch (\Exception $exception) {
            $results->addBadResult("\models\TCustomers::GetEntity(&#36;metadata,&#36;EM)", $exception->getMessage(), self::CODE);
        }
        try {
            $eCustomer = \models\TCustomers::GetEntity('customers', $metadata, $EM);
        }
        catch (\Exception $exception) {
            $results->addBadResult("\models\TCustomers::GetEntity('customers', &#36;metadata, &#36;EM)", $exception->getMessage(), self::CODE);
        }

        try {
            $eCustomer = \models\TEmptyModel::GetEntity($EM);
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
        $metadata = $this->_getSampleMetadata(self::FROM_OBJECT);
        
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

}
