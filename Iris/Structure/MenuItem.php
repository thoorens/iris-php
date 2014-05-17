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

    /**
     * This magic method replaces the old set and get methods:<ul>
     * <li> setUri, setId, setTitle, setLabel
     * <li> getUri, getId, getTitle, getLabel
     * </ul>
     * This permits to add other attributes to menu (not automatically managed in the standard
     * helper.
     * 
     * @param string $name
     * @param string[] $arguments
     * @return \Iris\Structure\MenuItem
     * @throws \Iris\Exceptions\MenuException
     */
    public function __call($name, $arguments) {
        $prefix = substr($name, 0,3 );
        $variable = substr($name,3);
        $variable[0] = strtolower($variable[0]);
        switch($prefix){
            case 'set':
                $this->_data[$variable] = $arguments[0];
                return $this;
            case 'get':
                $value = isset($this->_data[$variable]) ? $this->_data[$variable] : \NULL;
                return $value;
            default :
                throw new \Iris\Exceptions\MenuException('Invalid method call in menu : '.$name);
        }
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

    public function makeLink($alternateLabel = \NULL){
        if(empty($alternateLabel)){
            $params[] = $this->getLabel();
        }
        else{
            $getter = "get".$alternateLabel;
            $label = $this->$getter();
            if(is_null($label)){
                $label = $this->getTitle();
            }
            $params[] = $label;
        }
        $params[] = $this->getUri();
        $params[] = $this->getTitle();
        //return $params;
        return \Iris\views\helpers\Link::HelperCall('link', $params);
    }
}
