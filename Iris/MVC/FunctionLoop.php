<?php

namespace Iris\MVC;

use Iris\Engine as ie,
    Iris\Exceptions as ix;


/*
 * This file is part of IRIS-PHP.
 *
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IRIS-PHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IRIS-PHP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright 2012 Jacques THOORENS
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

    private $_functionName;

    /**
     *
     * @param string $functionName
     * @param type $data
     * @param type $key 
     */
    public function __construct($functionName, $data, $key =\NULL) {
        $this->_functionName = $functionName;
        parent::__construct('_none_', $data, $key);
    }

    /*
     * Returns the name of the directory where to find the script
     * corresponding to the partial
     * 
     * @return string
     */

    protected function _viewDirectory() {
        return "scripts/";
    }

    public function render($dummy=NULL) {
        ob_start();
        $prop = $this->_properties;
        $functionName = $this->_functionName;
        // Normal processing for associative array
        if(!is_numeric(key($prop))){
            foreach ($this->_properties as $key => $propertie) {
                if(!is_array($propertie)){
                    $propertie = $this->_properties;
                }
                echo $this->$functionName($propertie, $key);
            }
        }
        // non associative arrays may be processed too
        else {
            unset($this->_properties['CURRENTLOOPKEY']);
            foreach ($this->_properties as $propertie) {
                echo $this->$functionName($propertie);
            }
        }
        return ob_get_clean();
    }

}

