<?php

namespace modules\main\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

class test extends _main {

    /**
     * Set a layout at controller level
     */
    public function _init() {
        $this->_setLayout('controller');
    }

    public function metadataAction($entityName = 'customers') {
        $this->_setLayout('color');
        $this->__bodyColor = 'BLUE3';
        $em = \models\TInvoices::GetEM();
        $entityName = 'models\\'.$entityName;
        $entity = \Iris\DB\TableEntity::GetEntity($entityName);
        i_d($entity);
    }

    
}
