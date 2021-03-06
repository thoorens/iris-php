<?php
namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * Displays the database state and warning messages
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class DbLogo extends \Iris\views\helpers\_ViewHelper {

    protected $_singleton = TRUE;

    /**
     * Returns a link to the present RDBMS
     * 
     * @return string
     */
    public function help($changeURL) {
        $file = \Iris\Engine\Superglobal::GetSession('dbini', \Iris\DB\_EntityManager::DEFAULT_DBMS).'.png';
        return $this->callViewHelper('link','Link to other database',$changeURL,'Link to other databases','Logo')->image('/!documents/file/logos/'.$file);
    }

}
