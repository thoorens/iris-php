<?php

namespace modules\db\controllers;

/*
 * This file is part of IRIS-PHP distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * Description of tMetadata
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
trait tMetadata {

    /**
     * Gets a new metadata object 
     * 
     *  @return \Iris\DB\Metadata
     */
    protected function _getObjectMetadata() {
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
        return $metadata;
    }

    /**
     * Gets serialized metadata
     * 
     * @return string
     */
    protected function _getSerializedMetadata() {
        return <<<STRING
TABLE@customers 
FIELD@fieldName:id!type:INTEGER!size:0!defaultValue:!notNull:*TRUE*!autoIncrement:*TRUE*!primary:*TRUE*!foreignPointer:*NULL* 
FIELD@fieldName:Name!type:TEXT!size:0!defaultValue:!notNull:*TRUE*!autoIncrement:*FALSE*!primary:*FALSE*!foreignPointer:*NULL* 
FIELD@fieldName:Address!type:TEXT!size:0!defaultValue:!notNull:*TRUE*!autoIncrement:*FALSE*!primary:*FALSE*!foreignPointer:*NULL* 
FIELD@fieldName:Email!type:TEXT!size:0!defaultValue:!notNull:*TRUE*!autoIncrement:*FALSE*!primary:*FALSE*!foreignPointer:*NULL* 
PRIMARY@id
STRING;
    }

    /**
     * Gets metadata from a table
     * 
     * @param type $EM
     * @return \Iris\DB\Metadata
     */
    protected function _getTableMetadata($EM) {
        $tCustomers = \models\TCustomers::GetEntity($EM);
        $metadata = $tCustomers->getMetadata();
        // it is necessary to unregister the entity if we want use a new class for the entity
        $EM->unregisterEntity('customers');
        return $metadata;
    }

}
