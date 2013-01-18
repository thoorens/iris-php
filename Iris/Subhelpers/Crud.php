<?php

namespace Iris\Subhelpers;

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
 * This class is a subhelper for the helper Crud. 
 * Il is aimed to manage icons for a CRUD system.
 * Each main function has a special icon, which can
 * be active or not, have an action link and display an understandable 
 * tooltip. It is localized but must receive a pretranslated entity 
 * with appropriate gender mark.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Crud extends \Iris\Subhelpers\_Subhelper {
    const NOTHING = 0;
    const ID = 1;
    const PARAM = 2;

    protected static $_Instance = NULL;

    const ICON_SYSTEM_DIR = '/!documents/file/resource/images/icons';

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
     * The default icon directory. May be changed (but concerns all icons)
     * 
     * @var string
     */
    protected $_defaultIconDir = '/!documents/file/resource/images/icons';

    /**
     * The id field in data
     * @var string 
     */
    protected $_idField = 'id';

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
     * @var string Controller containing the method for CRUD
     */
    protected $_controller;

    /**
     *
     * @var type 
     */
    protected $_subType = '';

    /**
     *
     * @var array An associative array to provide format and parameter status
     */
    protected $_operationParams = array();

    protected function __construct() {
        $this->_operationParams = array(
            // key => array(format, status)
            'create' => array("Add %U %E| (type %P)", self::PARAM),
            'read' => array('Display %D %E %O', self::ID),
            'update' => array('Modify %D %E %O', self::ID),
            'delete' => array('Delete %D %E %O', self::ID),
            'upload' => array('Upload %D %E %O', self::ID),
            'first' => array('Go to the first «%E»', self::ID),
            'previous' => array('Go to the previous «%E»', self::ID),
            'next' => array('Go to the next «%E»', self::ID),
            'last' => array('Go to the last «%E»', self::ID),
        );
    }

    /**
     *
     * @param type $pilote
     * @return \Iris\Subhelpers\Crud 
     */
    public static function GetInstance($pilote = \NULL) {
        return parent::GetInstance($pilote);
    }

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
     * @return Crud   (fluent interface)
     */
    public function setEntity($entity) {
        $this->_entity = $entity;
        return $this;
    }

    /**
     * Sets an optional suffix for the action name 
     * @param String $name : the suffix added to action name
     * @return Crud    (fluent interface)
     */
    public function setActionName($name) {
        $this->_actionSuffix = $name;
        return $this;
    }

    /**
     * Provides a readable description of the type of object managed
     * @param string $descField
     * @return Crud   (fluent interface)
     */
    public function setDescField($descField) {
        $this->_descField = $descField;
        return $this;
    }

    /**
     * Sets the primary key field name
     * 
     * @param String $id
     * @return Crud   (fluent interface)
     */
    public function setIdField($id) {
        $this->_idField = $id;
        return $this;
    }

    /**
     * Stores the line values for use in help tooltip
     * 
     * @param mixed $data : un objet, ou un tableau où chercher des données
     * @return Crud   (fluent interface)
     */
    public function setData($data) {
        $this->_data = $data;
        return $this;
    }

    /**
     *
     * @param String $link : l'id de l'élément à modifier/utiliser
     * @return \Iris\views\helpers\CrudIcon (fluent interface)
     */
    public function setSubtype($link) {
        $this->_subType = $link;
        return $this;
    }

    /**
     * Permits to change the default icon dir (in ILO)
     * 
     * @param string $defaultIconDir 
     */
    public function setDefaultIconDir($defaultIconDir) {
        $this->_defaultIconDir = $defaultIconDir;
    }

    /**
     *
     * @param string $name
     * @param string $commentFormat
     * @param int $paramMode
     * @param string  $iconDir
     * @return Crud (fluent interface)
     */
    public function addFunction($name, $commentFormat, $paramMode = self::ID, $iconDir = \NULL) {
        $this->_operationParams[$name] = array($commentFormat, $paramMode, $iconDir);
        return $this;
    }

    /**
     * Returns a create link/icon
     * @return string
     */
    public function create($active=TRUE) {
        return $this->render('create', $active);
    }

    /**
     * Returns a read link/icon
     * @return string
     */
    public function read($active=TRUE) {
        return $this->render('read', $active);
    }

    /**
     * Returns a delete link/icon
     * 
     * @param boolean $active
     * @return string
     */
    public function delete($active=TRUE) {
        return $this->render('delete', $active);
    }

    /**
     * Returns an update link/icon
     * 
     * @param boolean $active
     * @return string
     */
    public function update($active=TRUE) {
        return $this->render('update', $active);
    }

    /**
     * Returns an upload link/icon
     * 
     * @param boolean $active
     * @return string 
     */
    public function upload($active=TRUE) {
        return $this->render('upload', $active);
    }

    /**
     * Creates an associative array with an URL (ref), a help text (help)
     * and the operation name (operation)
     * 
     * @param string $operation Operation name
     * @return array
     */
    public function prepare($operation) {
        $opParam = $this->_operationParams[$operation];
        // Additional operation may have a special directory for icons
        $params['dir'] = count($opParam) == 2 ? self::ICON_SYSTEM_DIR : $opParam[2];
        switch ($opParam[1]) {
            case self::ID:
                if (!is_null($this->_data)) {
                    $paramI = $this->_data->{$this->_idField};
                    $objectName = $this->_data->{$this->_descField};
                }
                else {
                    $objectName = "ENTITY";
                    $paramI = "id";
                }
                break;
            case self::PARAM:
                $paramI = $objectName = $this->_subType;
                break;
            default:
                $paramI = $objectName = '';
                break;
        }
        $format = $this->_treatCategory($opParam[0], $this->_subType);
        $params['ref'] = "$this->_controller/$operation" . "_" . "$this->_actionSuffix/" . $paramI;
        $params['help'] = $this->_makeHelp($format, $objectName);
        $params['operation'] = $operation;
        return $params;
    }
    
    public function testCLI($operation){
        $opParam = $this->_operationParams[$operation];
        $format = $this->_treatCategory($opParam[0], $this->_subType);
        $objectName = "someName";
        return $this->_makeHelp($format, $objectName);
    }

    /**
     * Gestion des artices en fonction du genre défini
     * 
     * @return string : two '_' separated articles (def. and undef.) 
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
     * Make the tooltip part of the icon/link
     * 
     * @param type $format
     * @param type $paramP
     * @param string $objectName
     * @return string 
     */
    private function _makeHelp($format, $objectName) {
        $aEntity = explode('_', $this->_entity);
        // if no gender, push Neutral
        if(count($aEntity)==1){
            array_unshift($aEntity, 'N_');
        }
        list($initialGender, $entity) = $aEntity;
        list($def, $undef) = explode('_', $this->_articles($initialGender));
        $format = str_replace('%U', $undef, $format);
        $format = str_replace('%D', $def, $format);
        $format = str_replace('%E', $entity, $format);
        $format = str_replace('%O', $objectName, $format);
        return $format;
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

    protected function _provideRenderer() {
        return \Iris\MVC\_Helper::HelperCall('crudIcon');
    }

}

?>
