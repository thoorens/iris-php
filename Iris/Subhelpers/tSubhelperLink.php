<?php

namespace Iris\Subhelpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
        if (is_null($this->_subhelper)) {
            $this->_initSubhelper();
        }
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

