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
 * @copyright 2012 Jacques THOORENS
 *
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * @todo : test it and maybe suppress
 */

/**
 * Creates an iconic link. The main differences with link(....)->image() are <ul>
 * <li> the possibility to put a clicable text after the icon
 * <li> the possibility (through method link2()) of a pair of icon that can switch when mouse is on.
 * <li> like link(), icons can have class, but no id.
 * </ul>
 * 
 *
 */
class Icon extends _ViewHelper {

    protected static $_Singleton = TRUE;
    protected $_baseDir;
    protected $_javaScript = FALSE;

    /**
     * By default, base directory is the one specified in settings
     */
    protected function _init() {
        $imageDir =  \Iris\SysConfig\Settings::$ImageFolder;
        $iconDir = \Iris\SysConfig\Settings::$IconDir;
        $this->_baseDir = "$imageDir/$iconDir";
    }

    /**
     * Creates an icon link or gets object instance to have access to public methods (no parameters provided)
     *
     * @param string $iconName The icon file name (without extension)
     * @param string $ref The URL of the link
     * @param string $help The tooltip text
     * @param type $desc The alt part of the icon img tab
     * @param type $iconText A text to be displayed after the icon
     * @param type $class An optional class for the image
     * @return \Iris\views\helpers\Icon
     */
    public function help($iconName = \NULL, $ref = \NULL, $help = \NULL, $desc = null, $iconText = '', $class = \NULL) {
        
        if (is_null($iconName)) {
            return $this;
        }
        else {
            return $this->link($iconName, $ref, $help, $desc, $iconText, $class);
        }
    }

    /**
     * Creates an icon link
     *
     * @param string $iconName The icon file name (without extension)
     * @param string $ref The URL of the link
     * @param string $help The tooltip text
     * @param type $desc The alt part of the icon img tab
     * @param type $iconText A text to be displayed after the icon
     * @param type $class An optional cl
     * @return string
     */
    public function link($iconName, $ref, $help, $desc = null, $iconText = '', $class = \NULL) {
        $desc = is_null($desc) ? $iconName : $desc;
        $icon = $this->callViewHelper('image', $this->_baseDir . "/$iconName.png", $desc, $help, '', $class) . $iconText;
        return '<a href="' . $ref . '">' . $icon . '</a>';
    }

    /**
     * Same as link but this an alternative icon name when mouse over
     *
     * @param string $iconName The icon file names (without extension) separated by '|'
     * @param string $ref The URL of the link
     * @param string $help The tooltip text
     * @param type $desc The alt part of the icon img tab
     * @param type $iconText A text to be displayed after the icon
     * @param type $class An optional cl
     * @return string
     */
    public function link2($iconNames, $ref, $help, $desc = null, $iconText = '', $class = \NULL) {
        list($icon1, $icon2) = explode('|', $iconNames);
        $desc = is_null($desc) ? $icon1 : $desc;
        $path1 = "/images" . $this->_baseDir . "/$icon1.png";
        $path2 = "/images" . $this->_baseDir . "/$icon2.png";
        $attributes = "onmouseover = \"this.src='$path2'\" ";
        $attributes .= "onmouseout=\"this.src='$path1'\" ";
        $icon = $this->callViewHelper('image', $this->_baseDir . "/$icon1.png", $desc, $help, '', $class, $attributes) . $iconText;
        return '<a href="' . $ref . '">' . $icon . '</a>';
    }

    /**
     * accesseur en écriture pour le répertoire de base des icônes
     *
     * @param type $baseDir : répertoire de base pour les icônes
     */
    public function setBaseDir($baseDir) {
        $this->_baseDir = $baseDir;
    }

}
