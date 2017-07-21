<?php

namespace Iris\Forms\Makers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * Common part of all AutoXElement classes serving as a description of an element in a form maker
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 */
class ElementMaker {

    use \Iris\Forms\tElement;

    /**
     * The form factory used to 
     * @var \Iris\Forms\_FormFactory 
     */
    public static $FormFactory;

    /**
     * The form in which insert the elements
     * @var \Iris\Forms\_Form
     */
    protected static $_Form;

    /* ---------------------------------------------------------------------------------------------------------------
     * Properties not contained in tElement
     * ---------------------------------------------------------------------------------------------------------------
     */

    /**
     *
     * @var type 
     */
    protected $_features;

    /**
     *
     * @var type 
     */
    protected $_size = '';

    /**
     *
     * @var type 
     */
    protected $_foreignPointer;

    /**
     *
     * @var type 
     */
    protected $_autoIncrement;

    /**
     *
     * @var type 
     */
    protected $_isNull;

    /**
     *
     * @var type 
     */
    protected $_isPrimary;

    /**
     *
     * @var type 
     */
    protected $_defaultValue;

    /**
     *
     * @var \Iris\DB\ForeignKey[]
     */
    static $ForeignPointers = [];

        
    /**
     *
     * @var \Iris\DB\_Entity
     */
    protected $_entity;
    
    /**
     * 
     */
    public function __construct($data) {
        if ($data instanceof \Iris\DB\MetaItem) {
            $this->_treatMetaItem($data);
        }
        else {
            self::$ForeignPointers = [];
            $this->_treatFeatures($data);
        }
    }

    public function setEntity($entity) {
        $this->_entity = $entity;
    }
    
    /**
     * 
     * @param \Iris\DB\MetaItem $metaItem
     */
    protected function _treatMetaItem($metaItem) {
        $this->_name = $metaItem->getFieldName();
        $this->_size = $metaItem->getSize();
        $this->_type = $metaItem->getType();
        $foreignPointerNumber = $metaItem->getForeignPointer();
        if (isset(self::$ForeignPointers[$foreignPointerNumber])) {
            $this->_foreignPointer = self::$ForeignPointers[$foreignPointerNumber];
        }
        $this->_autoIncrement = $metaItem->isAutoIncrement();
        $this->_isNull = $metaItem->isNotNull();
        $this->_isPrimary = $metaItem->isPrimary();
        $this->_defaultValue = $metaItem->getDefaultValue();
    }


    /**
     * Creates and inserts an element into the form through the form factory
     */
    public function addElement() {
        $factory = self::$_Form->getFormFactory();
        // simple element
        if (!$this->_foreignPointer instanceof \Iris\DB\ForeignKey) {
            $createMethod = $this->getMethodName();
            /* @var $internalElement \Iris\Forms\_Element */
            $internalElement = $factory->$createMethod($this->_name);
            $pairs = \NULL;
        }
        // element linked to another table
        else {
            $idKey = $this->_foreignPointer->getToKeys()[0];
            $parentEntityName = ucfirst($this->_foreignPointer->getTargetTable());
            $parentEntity = \Iris\DB\TableEntity::GetEntity($this->_entity->getEntityManager(), $parentEntityName);
            $identityField = $parentEntity::GetMainField();
            //i_d($parentEntity->getMetadata());
            if(empty($identityField)){
                $label = $parentEntity->getEntityName() . "#";
            }
            $list = $parentEntity->fetchall();
            foreach ($list as $le) {
                $pairs[$le->$idKey] = $identityField === \NULL ? $label.$le->$idKey : $le->$identityField;
            }
            $internalElement = $factory->createSelect($this->_name);
        }
        self::$_Form->addElement($internalElement);
        if (empty($this->_label)) {
            $internalElement->setLabel($this->_name . ':');
        }
        else {
            $internalElement->setLabel($this->_label);
        }
        $internalElement->setTitle($this->_title);
        $internalElement->addOptions($pairs);
    }

    /**
     * Sets the form used during conversion from autoelement to element
     * 
     * @param type $Form
     */
    public static function Prepare($Form) {
        self::$_Form = $Form;
    }

    public static function SetForeignPointers($foreignPointers) {
        self::$ForeignPointers = $foreignPointers;
    }

    

    /**
     * Accessor get for the name of the field
     * 
     * @return string
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Creates the form factory method corresponding to type
     * 
     * @return string
     */
    public function getMethodName() {
        return "create" . ucfirst(strtolower($this->_type));
    }

//    protected function _analyse(){
//        foreach($this->_featureNames as $key=>$name){
//            $internalName = "_$name";
//        }
//    }

    /**
     * Gets the value from features according to a key (with default value)
     * 
     * @param string $key
     * @param mixed $default
     * @return string
     */
    protected function _getValue($key, $default = \NULL) {
        if (isset($this->_features[$key])) {
            $value = $this->_features[$key];
        }
        else {
            $value = $default;
        }
        return $value;
    }

    /**
     * Converts all features of the element description to autoelement fields
     * 
     * @param type $data
     */
    protected function _treatFeatures($data) {
        $this->_features = $data;
        $short = [
            'type' => '@',
            'name' => 'N',
            'title' => 'T',
            'label' => 'L',
            'position' => 'P',
        ];
        foreach ($data as $key => $value) {
            if (strlen($key) > 1)
                $key = $short[$key];
            $this->_type = $this->_getValue('@', 'text');
            $this->_name = $this->_getValue('N', 'text');
            $this->_label = $this->_getValue('L', $this->_name . ":");
            $this->_title = $this->_getValue('T', '');
            $this->_size = $this->_getValue('S', '');
            //@todo Add other features
        }
    }

    public function setForeignPointer($foreignPointer) {
        $this->_foreignPointer($foreignPointer);
    }

}
