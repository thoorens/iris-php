<?php

namespace Iris\Forms\Elements;

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
 * An option in a select group
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Link extends \Iris\Forms\_Element {

    protected $_baseURL = "NONE";
    protected $_id;

    public function __construct($name,$id, $options = array()) {
        $this->_subtype = 'link';
        $this->_id = $id;
        $this->setLabel($name);
        $this->_labelPosition = self::NONE;
    }

    public function baseRender($key = \NULL) {
        $link = [$this->_label[0], "$this->_baseURL$this->_id", $this->_label[0]];
        $html = "\t\t" . \Iris\views\helpers\_ViewHelper::HelperCall('button', $link) . "\n";
        return $html;
    }

    public function setBaseURL($baseURL) {
        $this->_baseURL = $baseURL;
        return $this;
    }

}
