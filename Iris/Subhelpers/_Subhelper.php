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
 * A subhelper is a singleton class which realizes all the job for a view helper.
 * The helper returns a link to the subhelper. All the methods of the
 * subhelper are ready for use. The subhelper makes himself the rendering through whatever
 * method it chooses.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Subhelper implements \Iris\Design\iSingleton, \Iris\Translation\iTranslatable {
    use \Iris\Engine\tSingleton;
    use \Iris\Translation\tSystemTranslatable;
    use \Iris\views\helpers\tViewHelperCaller;

    
    /**
     * Returns the unique instance of the class and optionally gives it
     * a renderer
     * 
     * @return static 
     */
    public static function GetInstance() {
        if (is_null(static::$_Instance) or !static::$_Instance instanceof static) {
            static::$_Instance = new static();
        }
        return static::$_Instance;
    }

}

