<?php

namespace Iris\Forms;

use Iris\Forms\Validators as iv;

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
 * AutoElements permit to specify the layout and behavior of
 * a field in an autoform
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ElementSpecs implements \Iris\Translation\iTranslatable {

    use \Iris\Translation\tSystemTranslatable;

    private $_name;
    private $_label = \NULL;
    private $_type = 'text';
    private $_cols = 50;
    private $_rows = 5;
    private $_size = 25;
    private $_title = \NULL;
    private $_notShown = \FALSE;

//    Currently no supported in browsers
//    private $_width = 500;

    public function __construct($name, $params = []) {
        $this->_name = $name;
        $this->_inspectParams($params);
    }

    /**
     * 
     * @param _FormFactory $formFactory
     * @param \Iris\DB\MetaItem $metaItem
     */
    public function create($formFactory, $metaItem) {
        if ($this->_notShown) {
            $element = \NULL;
        }
        else {
            switch ($this->_type) {
                case 'textarea':
                    $element = $formFactory->createArea($this->_name);
                    $this->putCols($element);
                    $this->putRows($element);
                    break;
                case 'date':
                    $element = $formFactory->createDate($this->_name);
                    break;
                case 'checkbox':
                    $element = $formFactory->createCheckbox($this->_name);
                    break;
                default:
                    $element = $formFactory->createText($this->_name);
                    $this->putSize($element);
                    break;
            }
            $this->putLabel($element, $metaItem);
            $this->putTitle($element, $metaItem);
        }
        return $element;
    }

    /**
     * 
     * @param _Element $element
     * @param \Iris\DB\MetaItem $metaItem
     */
    private function putLabel($element, $metaItem) {
        $label = is_null($this->_label) ? $metaItem->getFieldName() : $this->_label;
        $element->setLabel($label);
    }

    public function __call($name, $args) {
        if (strpos($name, 'put') === 0) {
            $function = "set" . substr($name, 3);
            $value = "_" . strtolower(substr($name, 3));
            $args[0]->$function($this->$value);
        }
        elseif (strpos($name, 'set') === 0) {
            $variable = "_" . strtolower(substr($name, 3));
            if($variable == 'alt') die ('alt');
            $this->$variable = $args[0];
        }
        else {
            throw new \Iris\Exceptions\FormException("Unsupported method $name in AutoElement");
        }
    }

    
    public function setLabel($label) {
        $this->_label = $label;
        return $this;
    }

    public function setType($type) {
        $this->_type = $type;
        return $this;
    }

    public function setCols($cols) {
        $this->_cols = $cols;
        return $this;
    }

    public function setRows($rows) {
        $this->_rows = $rows;
        return $this;
    }

    public function setSize($size) {
        $this->_size = $size;
        return $this;
    }

//    Currently no supported in browsers
//    public function setWidth($width) {
//        $this->_width = $width;
//        return $this;
//    }

    public function getName() {
        return $this->_name;
    }

    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    public function setNotShown($notShown = \TRUE) {
        $this->_notShown = $notShown;
        return $this;
    }

    public function mustHide() {
        return $this->_notShown;
    }

    private function _inspectParams($params) {
        foreach ($params as $param) {
            list($setting, $value) = explode('=', $param.'=');
            switch($setting){
                case 'title':
                case 't':
                    $this->setTitle($value);
                    break;
                case 'type':
                case 'Y':
                    $this->setType($value);
                    break;
                case 'cols':
                case 'c':
                    $this->setCols($value);
                    break;
                case 'rows':
                case 'r':
                    $this->setRows($value);
                    break;
                case 'size':
                case 's':
                    $this->setSize($value);
                    break;
                case 'notshown':
                case 'n':
                    $this->setNotShown();
                    break;
                case 'label':
                case 'l':
                    $this->setLabel($value);
                    break;
                case '':
                    $this->
                    break;
                case '':
                    break;
            }
        }
    }

}
