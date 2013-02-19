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
 * internal function. It includes the Ajax functions.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class DNameSpace { // NameSpace is reserved

    use tRepository;

    const VARIABLE = 1;
    const FUNC = 2;

    private $_type;
    private $_value = \NULL;
    private $_args;

    /**
     * Syntactic sugar for GetObject
     * @param type $objectName
     * @return DNameSpace
     */
    public static function addObject($objectName){
        return self::GetObject($objectName);
    }
    
    public static function RenderAll() {
        $html = '';
        $objects = static::GetAllObjects();
        if (count($objects)) {
            $nameSpace = 'iris_dojo';
            $html .= "/* $nameSpace namespace */" . CRLF;
            $html .= "var $nameSpace = {};" . CRLF;
            foreach (static::GetAllObjects() as $object) {
                if ($object->_type == self::VARIABLE) {
                    $html .= "$nameSpace." . $object->_objectName;
                    if (!is_null($object->_value)) {
                        $html .= ' = ' . $object->_value;
                    }
                    $html .= ';' . CRLF;
                }
                else {
                    if(is_array($object->_args)){
                        $args = implode(',',$object->_args);
                    }
                    else{
                        $args = $object->_args;
                    }
                    $html .= "$nameSpace." . $object->_objectName;
                    $html .= " = function($args){" . CRLF;
                    $html .= $object->_value . CRLF;
                    $html .= '}' . CRLF;
                }
            }
        }
        return $html;
    }

    public function createVar($value = \NULL) {
        $this->_type = self::VARIABLE;
        $this->_value = $value;
    }

    public function createFunction($code, $args = []) {
        $this->_type = self::FUNC;
        $this->_value = $code;
        $this->_args = $args;
    }

}

