<?php

namespace Iris\controllers\helpers;

use Iris\DB\_EntityManager as DBEM;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Manages the database state and warning messages
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class DbOpen extends \Iris\controllers\helpers\_ControllerHelper {

    /**
     * 
     * @param boolean $return : if TRUE return the instance
     * @return wbClasses\AutoEM
     */
    public function help($return = \FALSE) {
        $dbini = \Iris\Engine\Superglobal::GetSession('dbini', DBEM::DEFAULT_DBMS);
        return DBEM::EMByNumber($dbini);

//        $instance = \wbClasses\AutoEM::GetInstance();
//        if ($return) {
//            return $instance;
//        {
    }

}
