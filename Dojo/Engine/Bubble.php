<?php

namespace Dojo\Engine;

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
 * This class is used internally by all Dojo helpers to manage the
 * components to load. Each bubble has its proper environment, prerequisites and
 * internal function
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Bubble {

    private static $_Repository = array();
    
    private $_bubbleName;
    
    private $_modules = array();

    /**
     * Returns a bubble (after creating it if necessary)
     * by its name
     * 
     * @param string $bubleName
     * @return Bubble
     */
    public static function GetBubble($bubbleName) {
        if(!isset(self::$_Repository[$bubbleName])){
            self::$_Repository[$bubbleName] = new Bubble($bubbleName);
        }
        return self::$_Repository[$bubbleName];
            
    }

    /**
     * 
     * @return array
     */
    public static function GetAllBubbles(){
        return self::$_Repository;
    }
    
    function __construct($bubbleName) {
        $this->_bubbleName = $bubbleName;
    }

    
    public function addModule($moduleName, $linked = \FALSE){
        $this->_modules[$moduleName] = $linked;
        return $this;
    }
    
    public function html(){
        $linkedModules = array();
        $unlinkedModules = array();
        $parameters = array();
        foreach($this->_modules as $name => $linked){
            if($linked!== \FALSE){
                $linkedModules[] = $name;
                $parameters[] = $linked;
            }else{
                $unlinkedModules[] = $name;
            }
        }
        $allModule = array_merge($linkedModules,$unlinkedModules);
        $html = 'require(["';
        $html .= implode('","',$allModule);
        $html .= '"]';
        if(count($linkedModules)>0){
            $html .= 'function(';
            $html .= implode(',',$linkedModules);
            $html .= '){some javascript}';
        }
        $html .= ');';
        return $html;
    }
}

