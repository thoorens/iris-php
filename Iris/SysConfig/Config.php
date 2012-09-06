<?php

namespace Iris\SysConfig;

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
 * A config is a container for some properties. It can be looked into
 * through foreach. Each property has its own value or can be inherited
 * from an ancestor. The values can be managed in files or database through 
 * a _Parser.
 * 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

class Config implements \IteratorAggregate {

    /**
     *
     * @var array : associative array containing the values of the 
     * config (inherited values may be in it or not according to inheritance mode) 
     * Always use the iterator or magic methods to access the actual data
     */
    protected $_data = array();

    /**
     *
     * @var Config : a reference to the parent (if it exists) 
     */
    protected $_parent = NULL;

    /**
     *
     * @var string : the config name as set by the constructor
     */
    protected $_name;
    private $_iterator;

    /**
     * Get the iterator for foreach loop
     * @return ConfigIterator 
     */
    public function getIterator() {
        return $this->_iterator;
    }

    /**
     * The constructor sets the name of the config
     * @param string $name : the config name (write only) 
     */
    public function __construct($name) {
        $this->_name = $name;
        $this->_iterator = new ConfigIterator($this);
    }

    /**
     * Return the name of the config
     * @return string 
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Magic method for property get
     * @param string $propertyName
     * @return mixed 
     */
    public function __get($propertyName) {
        if (isset($this->_data[$propertyName])) {
            return $this->_data[$propertyName];
        }
        // explicit parent may help to find property value
        elseif (!is_null($this->_parent)) {
            return $this->_parent->$propertyName;
        }
        return NULL;
    }

    /**
     * Magic method for property set
     * @param string $propertyName
     * @param mixed $value 
     */
    public function __set($propertyName, $value) {
        $this->_data[$propertyName] = $value;
        $this->_iterator->add($propertyName);
    }

    /**
     * Magic method for unset a property
     * @param type $propertyName 
     */
    public function __unset($propertyName) {
        unset($this->_data[$propertyName]);
        if (!is_null($this->_parent) and is_null($this->_parent->$propertyName)){
            $this->_iterator->remove($propertyName);
        }
    }

    /**
     * 
     * @param Config $parent
     * @param int $inheritance 
     */
    public function setParent($parent, $inheritance) {
        $this->_parent = $parent;
        foreach ($parent as $prop => $val) {
            if ($inheritance == _Parser::COPY_AND_LINK or
                    $inheritance == _Parser::COPY_INHERITED_VALUES) {
                $this->$prop = $val;
            }
            else {
                $this->getIterator()->add($prop);
            }
        }
    }

    /**
     *
     * @return Config 
     */
    public function getParent() {
        return $this->_parent;
    }

    public function isProperSetting($key) {
        if (is_null($this->_parent)) {
            return false;
        }
        return $this->_parent->$key == $this->$key;
    }

    public function isProperKey($key) {
        if (is_null($this->_parent)) {
            return true;
        }
        return!isset($this->_parent->_data[$key]);
    }

    /**
     * @param configs array(Config)
     */
    public static function Debug($configs, $color='red') {
        if (is_array($configs)) {
            foreach ($configs as $config) {
                $config->_debug($color);
            }
        }
        else {
            return $configs->_debug($color);
        }
    }

    protected function _debug($color='red') {
        echo '<h1>' . $this->getName() . '</h1>';
        /**
         * @var Config config
         */
        foreach ($this as $key => $value) {
            $parent = $this->getParent();
            if ($this->isProperSetting($key)) {
                $value = "<span style=\"{color:grey}\">$value</span>";
            }
            if ($this->isProperKey($key)) {
                $key = "<span style=\"{color:$color}\">$key</span>";
            }
            echo "<b>$key</b>: $value<br/>\n";
        }
    }

}

