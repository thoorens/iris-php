<?php

namespace IrisInternal\admin\controllers;

use \Iris\Users as u;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * In admin internal module, permits to display all current parameters for the application
 * <u>
 * <li> system settings
 * <li> dojo settings
 * <li> error settings
 * </ul>
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class parameters extends _admin {

    /**
     * Dispkays all system settings
     */
    public function systemAction() {
        $explorer = new \ReflectionClass('\Iris\SysConfig\Settings');
        $properties = $explorer->getStaticProperties();
        $this->__ParamTitle = 'System parameters';
        $this->__props = $this->_convertProps($properties);
        $this->setViewScriptName('display');
    }

    /**
     * Displays all Dojo settings
     */
    public function dojoAction() {
        $explorer = new \ReflectionClass('\Dojo\Engine\Settings');
        $properties = $explorer->getStaticProperties();
        $this->__ParamTitle = 'Dojo parameters';
        $this->__props = $this->_convertProps($properties);
        $this->setViewScriptName('display');
    }

    /**
     * Displays all error settings
     */
    public function errorAction() {
        $explorer = new \ReflectionClass('\Iris\Errors\Settings');
        $properties = $explorer->getStaticProperties();
        $this->__props = $this->_convertProps($properties);
        $this->__ParamTitle = 'Error parameters';
        $this->setViewScriptName('display');
    }

    /**
     * Converts all property values to strings
     * 
     * @param array $properties the statics properties of the Settings class
     * @return string
     */
    protected function _convertProps($properties) {
        foreach ($properties as $name => $prop) {
            if (is_array($prop)) {
                $props[$name] = 'ARR - ' . json_encode($prop);
            }
            elseif (is_object($prop)) {
                $props[$name] = 'OBJ - ' . json_encode($prop);
            }
            elseif (is_string($prop)) {
                $props[$name] = 'STR - ' . $prop;
            }
            elseif (is_bool($prop)) {
                $props[$name] = $prop ? 'BOO - TRUE' : 'BOO - FALSE';
            }
            elseif (is_numeric($prop)) {
                $props[$name] = 'NUM - ' . $prop;
            }
            elseif ($prop === \NULL) {
                $props[$name] = '???  - NULL';
            }
            else {
                $props[$name] = '??? - Indéterminé';
            }
        }
        return $props;
    }

}
