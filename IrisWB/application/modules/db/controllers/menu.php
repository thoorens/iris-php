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
 * Test of basic crud operations
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 */
class menu extends \Iris\MVC\_Subcontroller {

    /**
     * A menu for the left part of the screen
     */
    public function indexAction() {
        $this->__sqlite = \models\TInvoices::GetCurrentDbType() == \Iris\DB\_EntityManager::SQLITE;
    }

}
