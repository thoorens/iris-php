<?php

namespace CLI;

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
 * Common part for the localized help classes
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $/**
 * Common part for the localized help classes
 * 
 */
abstract class _Help {
    
    const NOPATH = 1;

    protected $_functions;


    function __construct($functions) {
        $this->_functions = $functions;
    }

    public function display($command) {
        $command = $command === FALSE ? 'help' : $command;
        switch ($command) {
            case 'help':case 'h':case'--':
                $this->_general();
                break;

            case 'createproject':case 'c':
                $this->_createProject();
                break;

            case 'alterproject':case'a':
                $this->_alterProject();
                break;

            case 'removeproject':case'r':
                $this->_removeProject();
                break;

            case 'show':case's':
                $this->_show();
                break;

            case 'url':case 'u':
                $this->_url();
                break;
            
            case 'mkcore':case 'k':
                $this->_mkCore();
                break;
            
            case 'searchcore':case 'K':
                $this->_searchCore();
                break;
                    

            /** ==============================================================================================
             * 
             */
            default:
                $this->_default($command);
        }
        die('');
    }

    public function showFunctions() {
        foreach ($this->_functions as $f => $function) {
            if ($f[strlen($f) - 1] == ':') {
                $f = substr($f, 0, strlen($f) - 1);
            }
            if ($function[strlen($function) - 1] == ':') {
                $function = substr($function, 0, strlen($function) - 1);
            }
            echo "\tiris.php -h=$function (ou -h=$f)\n";
        }
    }
    
    abstract protected function _general();

    abstract protected function _url();

    abstract protected function _createProject();

    abstract protected function _removeProject();

    abstract protected function _alterProject();

    abstract protected function _default($command);
    
    abstract protected function _show();
    
    abstract public function error($number);
    
    public function GetInstance(){
        return new self(array());
    }
}

?>
