<?php

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
 * A light subhelper is a subhelper that does make the final rendering
 * When the job is done, calling render()
 * will return the final aspect by using the helper that called the subhelper.
 * This helper must implement the interface \Iris\Subhelpers\iRenderer.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _LightSubhelper extends _Subhelper{

    /**
     *
     * @var \Iris\Subhelpers\iRenderer
     */
    protected $_renderer = NULL;
    

    /**
     * Gives the instance a formal aspect using the renderer provided 
     * or a default renderer.
     * 
     * @param type $arg1
     * @param type $arg2
     * @return type 
     */
    public final function render($arg1 = \NULL, $arg2 = \NULL) {
        $renderer = $this->_renderer;
        if (is_null($renderer)) {
            $renderer = $this->_getRenderer();
        }
        return $renderer->render($this->prepare($arg1), $arg2);
    }

    /**
     * This method must provide a default renderer (only if render() is used)
     * 
     * @return \Iris\Subhelpers\iRenderer 
     * @throws \Iris\Exceptions\NotSupportedException
     */
    abstract protected function _getRenderer();
    

    /**
     * @return array
     */
    public function prepare($arg1) {
        if (!is_array($arg1)) {
            return array($arg1);
        }
        else {
            return $arg1;
        }
    }

    /**
     * Returns the unique instance of the class and optionally gives it
     * a renderer
     * 
     * @param \Iris\Subhelpers\iRenderer $renderer
     * @return static 
     */
    public static function GetInstance($renderer = NULL) {
        if (is_null(static::$_Instance) or !static::$_Instance instanceof static) {
            static::$_Instance = new static();
        }
        if (!is_null($renderer)) {
            static::$_Instance->_renderer = $renderer;
        }
        return static::$_Instance;
    }

}

