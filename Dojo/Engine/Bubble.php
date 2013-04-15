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
 * @copyright 2011-2013 Jacques THOORENS
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
class Bubble {

    use tRepository;

    const TEXT = 1;
    const JSON = 2;
    const XML = 3;

    /**
     * A list of requisites for the bubble. 
     * @var array
     */
    private $_modules = array();

    /**
     *
     * @var type 
     */
    private $_internalFunction = \NULL;
    private $_done = \FALSE;

    /**
     * Syntaxic sugar for GetObject
     * 
     * @param string $objectName
     * @return Bubble
     */
    public static function GetBubble($objectName) {
        return self::GetObject($objectName);
    }

    /**
     * Adds a requisite to the bubble, corresponding to a Dojo module and
     * optionaly to a var in the corresponding function signature
     * 
     * @param string $moduleName the module name (with path)
     * @param mixed $linkedVar the variable mapped to the object created with the module
     * @return \Dojo\Engine\Bubble for fluent interface
     */
    public function addModule($moduleName, $linkedVar = \FALSE) {
        $this->_modules[$moduleName] = $linkedVar;
        return $this;
    }

    /**
     * Some modules are supported as special and identified by predefined constants
     * (eg JSON or XML). Text modules are implicit and doesn't need a specific module.
     * 
     * @param int $type The number corresponding to the speciale module.
     * @return \Dojo\Engine\Bubble for fluent interface
     */
    public function addSpecialModule($type) {
        switch ($type) {
            case self::JSON:
                $this->addModule('dojo/JSON', 'json');
                break;
            default:
        }
        return $this;
    }

    /**
     * Creates the javascript code for all bubbles in the application.
     * 
     * @return string
     */
    public function render($closed = \TRUE) {
        if (!$this->_done) {
            $linkedModules = array();
            $unlinkedModules = array();
            $parameters = array();
            foreach ($this->_modules as $name => $linkedVar) {
                if ($linkedVar !== \FALSE) {
                    $linkedModules[] = $name;
                    $parameters[] = $linkedVar;
                }
                else {
                    $unlinkedModules[] = $name;
                }
            }
            $allModule = array_merge($linkedModules, $unlinkedModules);
            $html = CRLF . "/* Dojo code for $this->_objectName */" . CRLF;
            $html .= 'require(["';
            $html .= implode('","', $allModule);
            $html .= '"]';
            if (count($linkedModules) > 0) {
                $functionText = $this->_internalFunction;
                $html .= ',function(';
                $html .= implode(',', $parameters);
                if ($closed) {
                    $html .= "){ $functionText }";
                }
            }
            if ($closed) {
                $html .= ');' . CRLF;
            }
            else {
                $html .= "){";
            }
            $this->_done = \TRUE;
            return $html;
        }
    }

    /**
     * Stores the text for the internal function of the bubble (only needed
     * in case the is linked modules and code to use them.
     * 
     * @param string $text
     */
    public function defFunction($text) {
        $this->_internalFunction = $text;
    }

    /**
     * Renders all objects in repository as a unique string
     * 
     * @return string
     */
    public static function RenderAll() {
        $text = '';
        foreach (self::GetAllObjects() as $bubble) {
            $text .= $bubble->render();
        }
        return $text;
    }

}

