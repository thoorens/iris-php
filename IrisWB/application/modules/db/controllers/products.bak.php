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
class products extends _dbCrud {

    
    public function showAction() {
        $tProducts = \models\TProducts::GetEntity();
        $this->__products = $tProducts->fetchall();
    }

    /**
     * In case of a broken database, the action is redirected here
     *
     * @param int $num
     */
    public function errorAction($num) {
        $this->setViewScriptName('commons/error');
    }

}
