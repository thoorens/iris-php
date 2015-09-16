<?php
namespace Iris\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A inited singleton has only on instance and must have a non public constructor
 * During its creation it must use the _init() method which must be defined in 
 * the current class
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
 */
trait tInitedSingleton {

    /**
     * Returns the unique instance or creates it if necessary.
     * 
     * @staticvar \static $Instance Serves to store the unique instance
     * @return static
     */
    public static function GetInstance() {
        static $Instance = \NULL;
        if (is_null($Instance)) {
            $Instance = new static();
            $Instance->_init();
        }
        return $Instance;
    }
    
    protected abstract function _init();

}


