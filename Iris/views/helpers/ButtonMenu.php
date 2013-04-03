<?php

namespace Iris\views\helpers;

use \Iris\Structure as is;

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
 *
 */

/**
 * Helper for rendering menus as a html <ul><li> recursive list
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * @todo definir un subhelper
 */
class ButtonMenu extends Menu {

    protected static $_ActiveClass = 'active';
    
    protected static $_MainTag = 'div';

    protected function _renderItem($item,$dummy) {
        $uri = $this->_simplifyUri($item['uri']);
        $label = $this->_($item['label']);
        $title = $this->_($item['title']);
        return $this->callViewHelper('button',$label,$uri,$title);
    }

}

