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
    public function help() {
        $instance = \wbClasses\AutoEM::GetInstance();
        switch($instance->getDbType()){
            case \Iris\DB\_EntityManager::SQLITE:
            case \wbClasses\AutoEM::SQLITE:
                $file = 'sqlite.png';
                break;
            case \wbClasses\AutoEM::MYSQL:
                $file = 'mySQL.png';
                break;
            case \wbClasses\AutoEM::POSTGRESQL:
                $file = 'postgresql.png';
                break;
        }
        return $this->callViewHelper('link','Link to other database','/db/sample/change','Link to other database','Logo')->image('/!documents/file/logos/'.$file);
        return $this->callViewHelper('link','/db/sample/'.$base,$file,'Logo',\NULL,')/!documents/file/logos/');
    }

}
