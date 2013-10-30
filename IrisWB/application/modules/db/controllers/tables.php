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

    protected function _init() {
        $this->_entityManager = \models\_invoiceManager::DefaultEntityManager();
        $this->setViewScriptName('/customers');
        $this->dbState()->validateDB();
    }

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

    /**
     * Verifies the syntax of TableEntity::GetEntity
     */
    public function testTableEntityAction() {
        $this->setViewScriptName('/tests');
        $EM = $this->_entityManager;
        $metadata = $this->_getSampleMetadata(self::FROM_OBJECT);

        $eCustomer = \Iris\DB\TableEntity::GetEntity('customers', $EM);
        $results["\Iris\DB\TableEntity::GetEntity('customers',&#36;EM)"] = 'OK';

        $eCustomer = \Iris\DB\TableEntity::GetEntity($metadata, $EM);
        $results["\Iris\DB\TableEntity::GetEntity(&#36;metadata,&#36;EM)"] = 'OK';

        try {
            $eCustomer = \Iris\DB\TableEntity::GetEntity($EM);
        }
        catch (\Exception $exception) {
            $results["\Iris\DB\TableEntity::GetEntity(&#36;EM)"] = $exception->getMessage();
        }

        try {
            $eCustomer = \Iris\DB\TableEntity::GetEntity('customers', $metadata, $EM);
        }
        catch (\Exception $exception) {
            $results["\Iris\DB\TableEntity::GetEntity('customers', &#36;metadata, &#36;EM)"] = $exception->getMessage();
        }

        try {
            $eCustomer = \Iris\DB\TableEntity::GetEntity('customers', 'invoices', $EM);
        }
        catch (\Exception $exception) {
            $results["\Iris\DB\TableEntity::GetEntity('customers', 'invoices',&#36;EM)"] = $exception->getMessage();
        }

        $this->__results = $results;
    }

    /**
     * Verifies the syntax of \models\Txxxxxx::GetEntity
     */
    public function testModelAction() {
        $this->setViewScriptName('/tests');
        $EM = $this->_entityManager;
        $metadata = $this->_getSampleMetadata(self::FROM_OBJECT);


        $eCustomer = \models\TCustomers::GetEntity($EM);
        $results["\models\TCustomers::GetEntity(&#36;EM)"] = 'OK';

        try {
            $eCustomer = \models\TCustomers::GetEntity('customers', $EM);
        }
        catch (\Exception $exception) {
            $results["\models\TCustomers::GetEntity('customers',&#36;EM)"] = $exception->getMessage();
        }
        try {
            $eCustomer = \models\TCustomers::GetEntity($metadata, $EM);
        }
        catch (\Exception $exception) {
            $results["\models\TCustomers::GetEntity(&#36;metadata,&#36;EM)"] = $exception->getMessage();
        }
        try {
            $eCustomer = \models\TCustomers::GetEntity('customers', $metadata, $EM);
        }
        catch (\Exception $exception) {
            $results["\models\TCustomers::GetEntity('customers', &#36;metadata, &#36;EM)"] = $exception->getMessage();
        }


        $this->__results = $results;
    }

    /**
     * Verifies the syntax of _Entity::GetEntity
     */
    public function testEntityAction() {
        $this->setViewScriptName('/tests');
        $EM = $this->_entityManager;
        $metadata = $this->_getSampleMetadata(self::FROM_OBJECT);

        try {
            $eCustomer = \Iris\DB\_Entity::GetEntity('customers', $EM);
        }
        catch (\Exception $ex) {
            $results["\Iris\DB\_Entity::GetEntity('customers',&#36;EM)"] = $ex->getMessage();
        }
        try {
            $eCustomer = \Iris\DB\_Entity::GetEntity($metadata, $EM);
        }
        catch (\Exception $ex) {
            $results["\Iris\DB\_Entity::GetEntity(&#36;metadata,&#36;EM)"] = $ex->getMessage();
        }
        try {
            $eCustomer = \Iris\DB\_Entity::GetEntity($EM);
        }
        catch (\Exception $ex) {
            $results["\Iris\DB\_Entity::GetEntity(&#36;EM)"] = $ex->getMessage();
        }
    
        $this->__results = $results;
    }

    

    /**
     * Common work on entity
     * 
     * @param \Iris\DB\_Entity $eCustomer
     */
    protected function _commonWorkOnCustomers($eCustomer) {
        $this->__customer2 = $eCustomer->find(2);
        $eCustomer->where('Name =', 'Antonio Sanchez');
        $this->__customer3 = $eCustomer->fetchRow();
        $this->__customers = $eCustomer->fetchAll();
        $this->__Entity = get_class($eCustomer);
    }

}
