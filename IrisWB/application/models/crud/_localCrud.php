<?php

namespace models\crud;

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
 * Test of basic crud operations on invoices
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _localCrud extends \Iris\DB\DataBrowser\_Crud {
 
    public static function __ClassInit() {
        $EM = \models\TInvoices::GetEM();
        static::$_EntityManager = $EM;
    }
    
    
    protected function _postUpdate($object) {
        $this->_setModified();
    }

    protected function _postDelete(&$object) {
        $this->_setModified();
    }
    
    protected function _postCreate($object) {
        $this->_setModified();
    }
    
    private function _setModified(){
        \Iris\controllers\helpers\_ControllerHelper::HelperCall('dbState', [],\NULL)->setModified();
    }
}
