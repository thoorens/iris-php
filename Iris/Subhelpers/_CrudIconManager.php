<?php

namespace Iris\Subhelpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This class is a subhelper for the helper Crud.
 * Il is aimed to manage icons for a CRUD system.
 * Each main function has a special icon, which can
 * be active or not, have an action link and display an understandable
 * tooltip. It is localized but must receive a pretranslated entity name
 * with appropriate gender mark.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _CrudIconManager extends \Iris\Subhelpers\_Subhelper {

    protected static $_Instance = NULL;

    /**
     * The entity name (in user's terminology) with a gender mark
     *
     * @var string
     */
    protected $_entity;

    /**
     * Optional action suffix (permitting to have various series of icons in
     * the same controller)
     *
     * @var string
     */
    protected $_actionSuffix;

    /**
     * The id field in data
     * @var string
     */
    protected $_idFields = ['id'];

    /**
     *
     * @var \Iris\DB\Object
     */
    protected $_data;

    /**
     * The field containing a meaningful description in data
     *
     * @var string
     */
    protected $_descField;

    /**
     *
     * @var string Controller containing the methods for CRUD
     */
    protected $_controller;

    /**
     * An optional category for a new object
     * 
     * @var string
     */
    protected $_subCategory = '';

    /**
     * An optional category description
     * 
     * @var string
     */
    protected $_subCategoryDesc;

    /**
     * May force a language for explanations (this feature is required by IrisWB which is independant from context)
     *
     * @var string
     */
    protected $_language = '';

    /**
     * An associative array to provide format and parameter status
     *
     * @var string[]
     */
    protected $_icons = [];

    /**
     * The directory containing the system icons
     * @var string
     */
    protected $_systemIconDir = \NULL;

    /**
     * The directory containing the extended icons
     * @var string
     */
    protected $_iconDir = \NULL;

    /**
     * A parameter corresponding to %P in URL
     * 
     * @var string
     */
    protected $_param;

    protected function __construct() {
        // Main CRUD icons
        $this->insert(new Icon('create', "Add %U %E| (type %P)", '%P'));
        $this->insert(new Icon('update', 'Modify %D %E %O', '%I'));
        $this->insert(new Icon('read', 'Display %D %E %O', '%I'));
        $this->insert(new Icon('delete', 'Delete %D %E %O', '%I'));
        $this->insert(new Icon('upload', 'Upload %D %E %O', '%I'));
        // extended CRUD icons
        $this->insert(new Icon('first', 'Go to the first «%E»'));
        $this->insert(new Icon('previous', 'Go to the previous «%E»', '%I'));
        $this->insert(new Icon('next', 'Go to the next «%E»', '%I'));
        $this->insert(new Icon('last', 'Go to the last «%E»'));
        // subclasses may add new icons
        $this->_init();
        if (is_null($this->_iconDir)) {
            $this->_iconDir = \Iris\SysConfig\Settings::$Icon();
        }
        if (is_null($this->_systemIconDir)) {
            throw new \Iris\Exceptions\HelperException('CrudIconManager must set values to iconDir and systemIconDir');
        }
    }

    /**
     * Add a new icon to the icon group
     * @param Icon $icon
     */
    public function insert($icon) {
        $name = $icon->getName();
        $this->_icons[$name] = $icon;
    }

    /**
     * Accessor get for the system icon folder
     * 
     * @return string
     */
    public function getSystemIconDir() {
        return $this->_systemIconDir;
    }

    /**
     * Accessor get for the extended icon folder
     * @return string
     */
    public function getIconDir() {
        return $this->_iconDir;
    }

    /**
     * Accessor set for the extended icon folder
     * 
     * @param string $iconDir
     * @return \Iris\Subhelpers\_CrudIconManager for fluent interface
     */
    public function setIconDir($iconDir) {
        $this->_iconDir = $iconDir;
        return $this;
    }

    /**
     *
     * @param type $pilote
     * @return \Iris\Subhelpers\_CrudIconManager
     */
//    public static function GetInstance($pilote = \NULL) {
//        return parent::GetInstance($pilote);
//    }

    /**
     * Set the name of the controller which will manage all four operations
     * @param string $controller
     * @return \Iris\views\helpers\CrudIcon  (fluent interface)
     */
    public function setController($controller) {
        $this->_controller = $controller;
        return $this;
    }

    /**
     *
     * @param String $entity : le nom de l'objet manipulé
     * @return CrudIconManager   (fluent interface)
     */
    public function setEntity($entity) {
        $this->_entity = $this->_($entity);
        return $this;
    }

    /**
     * Sets an optional suffix for the action name
     * @param String $name : the suffix added to action name
     * @return CrudIconManager    (fluent interface)
     */
    public function setActionName($name) {
        $this->_actionSuffix = $name;
        return $this;
    }

    /**
     * Provides a readable description of the type of object managed
     * @param string $descField
     * @return CrudIconManager   (fluent interface)
     */
    public function setDescField($descField) {
        $this->_descField = $descField;
        return $this;
    }

    /**
     * Sets the primary key field name
     *
     * @param String $id
     * @return CrudIconManager   (fluent interface)
     */
    public function setIdField($id) {
        if (!is_array($id)) {
            $id = [$id];
        }
        $this->_idFields = $id;
        return $this;
    }

    /**
     * Stores the line values for use in help tooltip
     *
     * @param mixed $data : un objet, ou un tableau où chercher des données
     * @return CrudIconManager   (fluent interface)
     */

    /**
     * 
     * @param \Iris\DB\Object $data
     * @param string $param A extra parameter corresponding to %P 
     * @return \Iris\Subhelpers\_CrudIconManager
     */
    public function setData($data, $param = \NULL) {
        $this->_data = $data;
        $this->_param = $param;
        return $this;
    }

    /**
     * An optional subcategory used by create
     *  
     * @param string $subCategory The sbbcategory name
     * @return \Iris\Subhelpers\_CrudIconManager (fluent interface)
     */
    public function setSubtype($subCategory, $subCategoryDesc = '') {
        if ($subCategoryDesc == '') {
            $subCategoryDesc = $subCategory;
        }
        $this->_subCategory = $subCategory;
        $this->_subCategoryDesc = $subCategoryDesc;
        return $this;
    }

    /**
     * Permits to change the default icon dir (in ILO)
     *
     * @param string $defaultIconDir
     * @return \Iris\Subhelpers\_CrudIconManager for fluent interface
     */
    public function setDefaultIconDir($defaultIconDir) {
        $this->_defaultIconDir = $defaultIconDir;
        return $this;
    }

    /**
     * May force a language (feature required by IrisWB)
     *
     * @param string $language
     * @return \Iris\Subhelpers\_CrudIconManager for fluent interface
     */
    public function forceLanguage($language) {
        $this->_language = $language;
        return $this;
    }

    public function __call($name, $arguments) {
        $active = count($arguments) ? $arguments[0] : \TRUE;
        /* @var $icon Icon */
        if (!isset($this->_icons[$name])) {
            throw new \Iris\Exceptions\HelperException('Operation unknow in CrudIconManager: ' . $name);
        }
        $icon = $this->_icons[$name];
        return $icon->render($active);
    }

    /**
     * Creates the ref part of the link
     * 
     * @param Icon $icon
     * @return string
     */
    public function makeReference($icon) {
        // small facility for IrisWB crudlinks test
        if (!$icon instanceof $icon) {
            $icon = $this->_icons[$icon];
        }
        $operation = $icon->getName();
        $urlParam = $icon->getUrlParam();
        if ($urlParam == '%I') {
            if ($this->_data instanceof \Iris\DB\Object) {
                foreach ($this->_idFields as $idField) {
                    $id[] = $this->_data->{$idField};
                }
                $id = implode('/', $id);
            }
            elseif (is_array($this->_data)) {
                foreach ($this->_idFields as $idField) {
                    $id[] = $this->_data[$idField];
                }
                $id = implode('/', $id);
            }
            else {
                $id = '';
            }
        }
        elseif ($urlParam == '%P') {
            $id = $this->_subCategory;
        }
        // in some case (e.a. IrisWB demo), we need to go back to icon to have $urlParam
        elseif ($urlParam == '?') {
            $urlParam = $this->_icons[$operation]->getUrlParam();
            return $this->makeReference($operation, $urlParam);
        }
        else {
            $id = '';
        }
        if (!is_null($icon->getSpecialUrl())) {
            return $icon->getSpecialUrl() . '/' . $id;
        }
        else {
            return "$this->_controller/$operation" . "_" . "$this->_actionSuffix/" . $id;
        }
    }

    /**
     * Make the tooltip part of the icon/link
     * 
     * @param type $operation
     * @return type
     */
    public function makeTooltip($operation) {
        $format0 = $this->_($this->_icons[$operation]->getTooltipTemplate());
        $format1 = $this->_treatCategory($format0, $this->_subCategoryDesc);
        $aEntity = explode('_', $this->_entity);
        // if no gender, push Neutral
        if (count($aEntity) == 1) {
            array_unshift($aEntity, 'N_');
        }
        list($initialGender, $entity) = $aEntity;
        list($defArticle, $undefArticle) = explode('_', $this->_articles($initialGender));
        $format2 = str_replace('%U', $undefArticle, $format1);
        $format3 = str_replace('%D', $defArticle, $format2);
        $format4 = str_replace('%E', $entity, $format3);
        if ($this->_data instanceof \Iris\DB\Object) {
            $object = $this->_data->{$this->_descField};
            $toolTip = str_replace('%O', $object, $format4);
        }
        elseif (is_array($this->_data)) {
            $object = $this->_data[$this->_descField];
            $toolTip = str_replace('%O', $object, $format4);
        }
        else{
            $toolTip = $format4;
        }
        return $toolTip;
    }

    /**
     * Gestion des artices en fonction du genre défini
     *
     * @return string : two '_' separated articles (def. and undef.)
     * @todo Plural?
     */
    private function _articles($initialGender) {
        switch ($initialGender) {
            case 'M':
                $gender = 'the_a';
                break;
            case "M'": // special case (an in english or l' in french)
                $gender = 'the_an';
                break;
            // for language with feminine
            case 'F':
                $gender = 'THE_A';
                break;
            case "F'": // special case (an in english or l' in french)
                $gender = 'THE_AN';
                break;
            // for languages with neuter
            case 'N':
                $gender = 'THE_a';
                break;
            case "N'":
                $gender = "THE_an";
                break;
            // no article
            default:
                $gender = "_";
                break;
        }
        // Translation here
        return strtolower($this->_($gender, TRUE));
    }

    /**
     * If format has a second part and category is provided, treats it
     * otherwise, erases it
     *
     * @param string $format
     * @param string $category
     * @return string
     */
    private function _treatCategory($format, $category) {
        $format = $this->_($format, TRUE);
        $splittedFormat = explode('|', $format);
        // no second part, take it all
        if (count($splittedFormat) == 1) {
            $newFormat = $format;
        }
        // parameter provided, remove '|'
        elseif ($category != '') {
            $format = implode('', $splittedFormat);
            $newFormat = str_replace('%P', $category, $format);
        }
        // otherwise take only first part
        else {
            $newFormat = $splittedFormat[0];
        }
        return $newFormat;
    }

    protected function _getRenderer() {
        return \Iris\MVC\_Helper::HelperCall('crudIcon');
    }

    /**
     *
     * @param string $message
     * @param boolean $system
     * @return type
     */
    public function _($message, $system = \TRUE) {
        if ($this->_language == 'english') {
            return $message;
        }
        else {
            return parent::_($message, $system);
        }
    }

    /**
     * This method may be overriden in subclasses
     */
    public function _init() {
        
    }

}
