<?php

namespace modules\db\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 *
 * Test of basic crud operations
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class kustomers extends _dbCrud {

    use \Iris\DB\DataBrowser\tCrudManager;

    protected function _init() {
        $this->_entityManager = \Iris\DB\_Entity::DefaultEntityManager();
    }


    /**
     *
     * @param string $type
     */
    public function showAction() {
        $tCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $this->setViewScriptName('/customers_show');
        $this->__customers = $tCustomers->fetchAll();
    }

    /**
     * In case of a broken database, the action is redirected here
     *
     * @param int $num
     */
    public function errorAction($num=0) {
        $this->setViewScriptName('commons/error');
    }

    protected function _preCustomize($actionName) {
        switch ($actionName) {
            case 'create':
                $this->__title = "Add a customer";
                $this->__crudFunction = "Anything";
                break;
        }
    }

   
}
