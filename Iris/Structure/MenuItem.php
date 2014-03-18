<?php

namespace Iris\Structure;

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
 * Class MenuItem is a way to represent a menu item
 * with URI, label, tootltip, visibility and mark for default. 
 * By extending Menu, it can contain a submenu.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class MenuItem extends Menu {

    protected $_data = array(
        'uri' => NULL,
        'label' => NULL,
        'title' => NULL,
        'visible' => TRUE,
        'default' => FALSE,
        'id' => ''
    );

    /**
     * The constructor permits to init the item
     * with a serialized string 
     * e.g. "/path/to/action|Label|Toottip"
     *  
     * @param string $data 
     */
    public function __construct($data = NULL) {
        if (is_string($data)) {
            list($uri, $label, $title) = explode('|', $data . "||");
            $this->setUri($uri)
                    ->setTitle($title)
                    ->setLabel($label);
        }
    }

    /**
     * Accessor get to 
     * @return string 
     */
    public function getLabel() {
        return $this->_data['label'];
    }

    /**
     * Accessor set to Label
     * 
     * @param string $label 
     * @return \Iris\Structure\MenuItem (for fluent interface)
     */
    public function setLabel($label) {
        $this->_data['label'] = $label;
        return $this;
    }

    /**
     * Accessor get for the optional id
     * 
     * @return string
     */
    public function getId() {
        return $this->_data['id'];
    }

    /**
     * Accessor set for the optional id
     * 
     * @param string $id
     * @return \Iris\Structure\MenuItem (for fluent interface)
     */
    public function setId($id) {
        $this->_data['id'] = $id;
        return $this;
    }

    /**
     * Accessor get to URI
     * 
     * @return string 
     */
    public function getUri() {
        return $this->_data['uri'];
    }

    /**
     * Accessor set to URI
     * 
     * @param string $uri 
     * @return \Iris\Structure\MenuItem (for fluent interface)
     */
    public function setUri($uri) {
        $this->_data['uri'] = $uri;
        return $this;
    }

    /**
     * Accessor get to Title (tooltip)
     * 
     * @return string
     */
    public function getTitle() {
        return $this->_data['title'];
    }

    /**
     * Accessor set to title (tooltip)
     * 
     * @param string $title 
     * @return \Iris\Structure\MenuItem (for fluent interface)
     */
    public function setTitle($title) {
        $this->_data['title'] = $title;
        return $this;
    }

    /**
     * Accessor set to Value
     * 
     * @param string $value 
     */
    public function setDefault($value = TRUE) {
        $this->_data['default'] = $value;
        return $this;
    }

    /**
     * Acessor set to visibility
     * 
     * @param boolean $value 
     */
    public function setVisible($value) {
        $this->_data['visible'] = $value;
        return $this;
    }

    /**
     * Return the item as an array
     * 
     * @param type $simplify
     * @return array 
     */
    public function asArray() {
        $data = $this->_data;
        foreach ($this->_items as $item) {
            $data['submenu'][] = $item->asArray();
        }
        return $data;
    }

}
