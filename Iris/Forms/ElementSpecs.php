<?php

namespace Iris\Forms;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * AutoElements permit to specify the layout and behavior of
 * a field in an autoform
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ElementSpecs { //implements \Iris\Translation\iTranslatable {

    use \Iris\Translation\tSystemTranslatable;

    /**
     * First positional parameter: the type of element
     */
    const TYPE = 0;
    /**
     * Second positional parameter: the label of the element
     */
    const LABEL = 1;

    /**
     * the element name
     * @var string
     */
    private $_name;
    /**
     * the element label
     * @var string
     */
    private $_label = \NULL;
    /**
     * The element type
     * @var string
     */
    private $_type = 'text';
    /**
     * The element width
     * @var int
     */
    private $_cols = 50;
    /**
     * The element height
     * @var int
     */
    private $_rows = 5;
    /**
     * The element size
     * @var int
     */
    private $_size = 25;
    /**
     * The element title (used by the tooltip)
     * @var string
     */
    private $_title = \NULL;
    /**
     * The element not shown status
     * @var boolean
     */
    private $_notShown = \FALSE;

//    Currently no supported in browsers
//    private $_width = 500;

    /**
     * An ElementSpecs has a name and maybe optional parameters
     * 
     * @param string $name The name of the element
     * @param array $params Some optional params added to the params listed in the ini file
     */
    public function __construct($name, $params = []) {
        $this->_name = $name;
        if (count($params)) {
            $this->_inspectParams($params);
        }
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

    

    /**
     * Analyses the content of the line to get the different parameter for the
     * element
     * 
     * @param string $line a multi value string with ! as an internal seperator
     */
    public function setSpecs($line) {
        $specs = explode('!', $line . '!!!!!');
        foreach ($specs as $index => $value) {
            switch ($index) {
                case self::TYPE:
                    //not_survive'YYYYES');
                    if ($value == '-') {
                        $this->setNotShown(\TRUE);
                        $this->setType('hidden');
                    }
                    else {
                        $this->setType($value);
                    }
                    break;
                case self::LABEL:
                    $this->setLabel($value);
                    break;
                default:
                    if($value>''){
//                        $this->_inspectParams($params);
                    }
            }
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

    public function setTitle($title) {
        $this->_title = $title;
        return $this;
    }


//    Currently no supported in browsers
//    public function setWidth($width) {
//        $this->_width = $width;
//        return $this;
//    }


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

        public function getName() {
        return $this->_name;
    }

    public function getType() {
        return $this->_type;
    }

        
    /**
     * Treats the parameters inited in the constructor
     * 
     * @param type $params
     */
    private function _inspectParams($params) {
        foreach ($params as $param) {
            list($setting, $value) = explode('=', $param . '=');
            switch ($setting) {
                case 'title':
                case 'T':
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

//    public function __call($name, $args) {
//not_survive'OLD _call has benn called');
//        if (strpos($name, 'put') === 0) {
//            $function = "set" . substr($name, 3);
//            $value = "_" . strtolower(substr($name, 3));
//            $args[0]->$function($this->$value);
//        }
//        elseif (strpos($name, 'set') === 0) {
//            $variable = "_" . strtolower(substr($name, 3));
//            $this->$variable = $args[0];
//        }
//        else {
//            throw new \Iris\Exceptions\FormException("Unsupported method $name in ElementSpecs");
//        }
//    }
    
}
