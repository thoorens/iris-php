<?php

namespace Iris\views\helpers;

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
 * @copyright 2011-2014 Jacques THOORENS
 */

/**
 * This helper will generate a 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class MagikLinx extends \Iris\views\helpers\_ViewHelper {

    protected $_modelClass = '\models\TLinks';

    public function help($name = \NULL, $internalFN = 'Internal', $labelFN = 'Label', $titleFN = 'Title', $URLFN = 'URL') {
        if (is_null($name)) {
            return $this;
        }
        $modelClass = $this->_modelClass;
        list($internal, $label, $title, $url) = $modelClass::GetMagikLink($name, $internalFN, $labelFN, $titleFN, $URLFN);
        $classInternal = $internal ? ' class="internal"' : '';
        if ($url == '') {
            $goodName = sprintf('<span class="magiklinx" title="%s">%s</span>', $title, $label);
        }
        else {
            $goodName = sprintf('<span class="magiklinx" title="%s"><a %s href="%s">%s</a></span>', $title, $classInternal, $url, $label);
        }
        return $goodName;
    }

    /**
     * Permits to change the default model class (normally \models\TLinks)
     * 
     * @param string $modelClass
     */
    public function setModelClass($modelClass) {
        $this->_modelClass = $modelClass;
    }

}
