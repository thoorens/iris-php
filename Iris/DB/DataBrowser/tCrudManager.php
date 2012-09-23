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
     * The simple way to change it consists in a single line in _init() method
     *      $this->_commonViewScript = 'whatyouwant";
     * or
     *       $this->_commonViewScript = \NULL;
     * 
     * @var string
     */
    protected $_commonViewScript = 'editall';

    /**
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

    private final function _changeViewScript($actionName) {
        if (is_null($this->_commonViewScript)) {
            $this->_commonViewScript = $actionName;
        }
    }

    private function _customize($actionName){
        
    }
    public function errorAction($num) {
        die("There is an error $num");
    }

}

