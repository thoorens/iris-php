<?php

namespace Iris\DB\DataBrowser;

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
 * This trait offers a controller the power to manage a table (CRUD operation)
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
trait tCrudManager {

    /**
     * By default, the script is common to all action and is named 'editall'. 
     * It can be set to another name or set to NULL. In that case, each script
     * takes the action name (update, delete, create, read).
     * 
     * The simple way to change it consists in a single line in _init() or 
     * customize() methods
     *      $this->_changeViewScript = 'whatyouwant";
     * or
     *       $this->_changeViewScript = \NULL;
     * 
     * @var string
     */
    protected $_commonViewScript = 'editall';

    /**
     * This magic method simulates the four createAction, updateAction....
     * methods using _Crud class.
     * 
     * @param string $actionName
     * @param type $parameters
     */
    public final function __callAction($actionName, $parameters) {
        $shortAction = preg_replace('/(.*)\_.*Action/', '$1', $actionName);
        $this->_changeViewScript($shortAction);
        \Iris\DB\DataBrowser\_Crud::DispatchAction($this, $actionName, $parameters, $this->_commonViewScript);
        $this->_customize($shortAction);
    }

    /**
     * Change the common view script for all 4 actions (if null, the default
     * update, delete, create, read will be used)
     * 
     * @param string $scriptName The action script name 
     */
    protected final function _changeViewScript($scriptName) {
        if (is_null($this->_commonViewScript)) {
            $this->_commonViewScript = $scriptName;
        }
    }

    /**
     * By overwriting this method, one can modify one or many implicit methods of the
     * CRUD manager or do whatever else (e.g. /IrisWB/application/modules/manager/controllers/screens.php)
     * 
     * 
     * @param string $actionName one among create/read/update/delete
     */
    protected function _customize($actionName){
        
    }
    
    /**
     * A default error action to be overwritten.
     * 
     * @param int $num
     */
    public function errorAction($num) {
        die("There is an error $num");
    }

}

