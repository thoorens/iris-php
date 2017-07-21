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
        $this->callViewHelper('subtitle', 'Databases');
        $this->__changeURL='/db/sample/change';
    }

    /**
     * In case of a broken database, the action is redirected here
     * 
     * @param int $num
     */
    public function errorAction($num = 0) {
        $this->__Title = "Error in database";
        $this->setViewScriptName('/commons/error');
    }

    public static function GetSampleMetadata($mode) {
        switch ($mode) {



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
                break;
        }
        return $metadata;
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
