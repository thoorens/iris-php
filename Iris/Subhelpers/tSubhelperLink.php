<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Iris\Subhelpers;

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
 * This trait complements the iRenderer interface with a subhelper supply
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
trait tSubhelperLink{
    
    private $_subhelper = \NULL;

    /**
     * Must be defined in main class
     */
    //protected $_subhelperName = ...;
    
    protected function _subclassInit(){
        self::$_Singleton = \TRUE;
    }
    
    /**
     * Returns the instance to get its pseudo variable (using __get) or its
     * methods
     * 
     * @return Error
     */
    public final function help() {
        if(is_null($this->_subhelper))
            $this->_initSubhelper();
        return $this->_subhelper;
    }
    
    /**
     * The mechanism to find the subhelper: here it finds the class through the HelperName
     * but this can be changed in subclasses
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    private final function _initSubhelper(){
        if(!isset($this->_subhelperName)){
            throw new \Iris\Exceptions\InternalException
            ('The renderer '.__CLASS__.' must define its associated subhelper through $_subhelperName var');
        }
        $subhelper = $this->_subhelperName;
        $this->_subhelper = $subhelper::GetInstance($this);
    }
}

