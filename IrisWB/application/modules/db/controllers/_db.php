<?php

namespace modules\db\controllers;

use Iris\DB\Query;

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
 */

/**
 * Description of simple
 * 
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * 
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ */
abstract class _db extends \modules\_application {

    const FROM_TABLE = 1;
    const FROM_STRING = 2;
    const FROM_OBJECT = 3;

    /**
     *
     * @var \Iris\DB\_EntityManager
     */
    protected $_entityManager;

    /**
     * Prepares all treatments:<ul>
     * <li>inits menu and layout
     * <li>prepares database status</ul>
     */
    protected function _moduleInit() {
        $this->_setLayout('database');
        $this->registerSubcontroller(1, 'menu');
        $this->callViewHelper('subtitle','Databases');
    }

    
    /**
     * In case of a broken database, the action is redirected here
     * 
     * @param int $num
     */
    public function errorAction($num) {
        $this->__Title = "Error in database";
        $this->setViewScriptName('/commons/error');
    }

    public static function GetSampleMetadata($mode) {
        switch ($mode) {

            case self::FROM_STRING:
                return <<<STRING
TABLE@customers 
FIELD@fieldName:id!type:INTEGER!size:0!defaultValue:!notNull:*TRUE*!autoIncrement:*TRUE*!primary:*TRUE*!foreignPointer:*NULL* 
FIELD@fieldName:Name!type:TEXT!size:0!defaultValue:!notNull:*TRUE*!autoIncrement:*FALSE*!primary:*FALSE*!foreignPointer:*NULL* 
FIELD@fieldName:Address!type:TEXT!size:0!defaultValue:!notNull:*TRUE*!autoIncrement:*FALSE*!primary:*FALSE*!foreignPointer:*NULL* 
FIELD@fieldName:Email!type:TEXT!size:0!defaultValue:!notNull:*TRUE*!autoIncrement:*FALSE*!primary:*FALSE*!foreignPointer:*NULL* 
PRIMARY@id
STRING;

            case self::FROM_TABLE:
                $EM = \models\_invoiceManager::DefaultEntityManager();
                $tCustomers = \models\TCustomers::GetEntity($EM);
                $metadata = $tCustomers->getMetadata();
                // it is necessary to unregister the entity if we want use a new class for the entity
                $EM->unregisterEntity('customers');
                return $metadata;

            case self::FROM_OBJECT:
                $metadata = new \Iris\DB\Metadata('customers');

                // id
                $idFied = new \Iris\DB\MetaItem('id');
                $idFied
                        ->setNotNull()
                        ->setType('INTEGER')
                        ->setAutoIncrement()
                        ->setPrimary();
                $metadata->addItem($idFied);

                // Name
                $nameField = new \Iris\DB\MetaItem('Name');
                $nameField
                        ->setNotNull()
                        ->setType('TEXT');
                $metadata->addItem($nameField);

                // Address
                $addressField = new \Iris\DB\MetaItem('Address');
                $addressField
                        ->setNotNull()
                        ->setType('TEXT');
                $metadata->addItem($addressField);

                // Email
                $emailField = new \Iris\DB\MetaItem('Email');
                $emailField
                        ->setNotNull()
                        ->setType('TEXT');
                $metadata->addItem($emailField);

                $metadata->addPrimary('id');
                //iris_debug($metadata->serialize());
                return $metadata;
        }
    }

    protected function _getSampleMetadata($mode) {
        return self::GetSampleMetadata($mode);
    }

    
    /**
     * Common work on entity
     * 
     * @param \Iris\DB\_Entity $eCustomer
     */
    protected function _commonWorkOnCustomers($eCustomer, $Sanchez = \TRUE) {
        $name = $Sanchez ? 'Antonio Sanchez' : 'John Smith';
        $this->__customer2 = $eCustomer->find(2);
        $eCustomer->where('Name =', $name);
        $this->__customer3 = $eCustomer->fetchRow();
        $this->__customers = $eCustomer->fetchAll();
        $this->__Entity = get_class($eCustomer);
    }
}
