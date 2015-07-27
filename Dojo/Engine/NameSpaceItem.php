<?php

namespace Dojo\Engine;
use Iris\System\tRepository;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This class is used to simulate namespaces in javascript. Each item belongs
 * to a namespace (by default iris_dojo) and can be a variable or a function.
 * Some functions are generated during last stage of page rendering.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class NameSpaceItem { 
    // adds multiple items management in a collection
    use tRepository;

    /*
     * 3 types of items
     */
    /**
     * variable type
     */
    const VARIABLE = 1;
    /**
     * normal function type
     */
    const FUNC = 2;
    /**
     * late generated function type
     */
    const LATEFUNC = 3;
    
    /**
     * The default namespace
     */
    const DEFAULTNS = 'iris_dojo';

    /**
     * The item type
     * @var int
     */
    private $_type;
    /**
     * The item value (or provider for the value)
     * @var mixed
     */
    private $_value = \NULL;
    /**
     * The javascript params for a function
     * @var string 
     */
    private $_args;
    /**
     * The namespace to which the item belongs
     * 
     * @var string
     */
    private $_nameSpace;
    
    /**
     * The list of all different namespace
     * 
     * @var string[]
     */
    private static $_NameSpaces = [];

    /**
     * Syntactic sugar for GetObject since the GetObject creates an
     * unexistant object.
     * 
     * @param type $objectName
     * @return NameSpaceItem
     */
    public static function AddObject($objectName) {
        return self::_GetObject($objectName);
    }

    /**
     * Creates a javascript code declaring all the namespaces
     * and all the variables and functions  they contain.
     * 
     * @return string
     */
    public static function RenderAll() {
        $html = '';
        $objects = static::GetAllObjects();
        if (count($objects)) {
            //  creates all the namespaces
            foreach (self::$_NameSpaces as $nameSpace) {
                $html .= "/* $nameSpace namespace */" . CRLF;
                $html .= "var $nameSpace = {};" . CRLF;
            }
            /* @var $object NameSpaceItem */
            foreach (static::GetAllObjects() as $object) {
                $nameSpace = $object->_nameSpace;
                // creates a variable
                if ($object->_type == self::VARIABLE) {
                    $html .= "$nameSpace." . $object->_objectName;
                    if (!is_null($object->_value)) {
                        $html .= ' = ' . $object->_value;
                    }
                    $html .= ';' . CRLF;
                }
                else {
                    // creates a function
                    if (is_array($object->_args)) {
                        $args = implode(',', $object->_args);
                    }
                    else {
                        $args = $object->_args;
                    }
                    if ($object->_type == self::FUNC) {
                        $code = $object->_value;
                    }
                    else { // type : self::LATEFUNC
                        // for late functions, the code has to be generated only now
                        /* @var $provider \Dojo\Engine\iLateScriptProvider */
                        $provider = $object->_value;
                        $code = $provider->getlateScript();
                    }
                    $html .= "$nameSpace." . $object->_objectName;
                    $html .= " = function($args){" . CRLF;
                    $html .= $code . CRLF;
                    $html .= '}' . CRLF;
                }
            }
        }
        return $html;
    }

    /**
     * Specifies an item as a variable, possibly initialized
     * 
     * @param string $value The optional initial value (as javascript source code)
     * @param string $nameSpace The optional namespace
     */
    public function createVar($value = \NULL, $nameSpace = \NULL) {
        $this->_setNameSpace($nameSpace);
        $this->_type = self::VARIABLE;
        $this->_value = $value;
    }

    /**
     * Specifies an item as a function 
     * 
     * @param string $code The text of the code for the function
     * @param string $args The arguments for the function (as javascript source code)
     * @param string $nameSpace The optional namespace
     */
    public function createFunction($code, $args = '', $nameSpace = \NULL) {
        $this->_setNameSpace($nameSpace);
        $this->_type = self::FUNC;
        $this->_value = $code;
        $this->_args = $args;
    }

    /**
     * Specifies an item as a function (whose code is to be generated later)
     * 
     * @param \Dojo\Engine\iLateScriptProvider $provider
     * @param string[] $args The arguments for the function (as javascript source code)
     * @param string $nameSpace The optional namespace
     */
    public function createLateFunction(\Dojo\Engine\iLateScriptProvider $provider, $args = '', $nameSpace = \NULL) {
        $this->_setNameSpace($nameSpace);
        $this->_type = self::LATEFUNC;
        $this->_value = $provider;
        $this->_args = $args;
    }

    /**
     * Accessor set for the namespace (if null, takes the default one)
     * Internal usage.
     * 
     * @param string $nameSpace
     */
    private function _setNameSpace($nameSpace) {
        if (is_null($nameSpace))
            $nameSpace = self::DEFAULTNS;
        $this->_nameSpace = $nameSpace;
        // register the namespace
        self::$_NameSpaces[$nameSpace] = $nameSpace;
    }

}

