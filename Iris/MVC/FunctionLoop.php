<?php

namespace Iris\MVC;

use Iris\Engine as ie,
    Iris\Exceptions as ix;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A view which manages a small part of the page. It can be reused
 * or used in a loop
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class FunctionLoop extends \Iris\MVC\Partial {

    /**
     * Type of view
     * 
     * @var string
     */
    protected static $_ViewType = 'loop';
    private $_functionCode;

    /**
     *
     * @param string $functionCode
     * @param type $data
     * @param type $key 
     */
    public function __construct($functionCode, $data, $key = \NULL) {
        $this->_functionCode = $functionCode;
        parent::__construct('_none_', $data, $key);
    }

    /*
     * Returns the name of the directory where to find the script
     * corresponding to the partial
     * 
     * @return string
     */

    public function viewDirectory() {
        return "scripts/";
    }

    public function render($forcedScriptName = NULL, $absolute = \FALSE) {
        ob_start();
        $prop = $this->_properties;
        $functionCode = $this->_functionCode;
        // Normal processing for associative array
        foreach ($this->_properties as $key => $propertie) {
            if (is_array($functionCode)) {
                list($class, $method) = $functionCode;
                echo $class->$method($propertie, $key);
            }
            else {
                echo $functionCode($propertie, $key);
            }
        }
        return ob_get_clean();
    }

}
