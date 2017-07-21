<?php

namespace Iris\Documents;
/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * A subclass of \Iris\DB\DataBrowser\Upload which permits to add informations
 * such as upload date, user id, file name, folder name, availability
 * and ACL
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ExtendedUpload extends \Iris\DB\DataBrowser\Upload {
    const TO = 0;
    const FROM = 1;

    /**
     * the user class to put document informations into
     * (it must be a subclass of "\\Iris\\DB\Document")
     * 
     * @var string
     */
    private $_EntityName = "\\Iris\\DB\Document";

    /**
     * mapping for the document category key
     * @var string
     */
    private $_category_id = 'category_id';

    /**
     * mapping for the document owner key 
     * @var string
     */
    private $_user_id = "user_id";

    /**
     * mapping for the document description field
     * @var string
     */
    private $_CategoryName = 'CategoryName';

    /**
     * mapping for the document file name field
     * @var string
     */
    private $_File = 'File';

    /**
     * mapping for the document file validation flag field
     * @var string
     */
    private $_Validation = 'Validation';

    /**
     * mapping for the document upload date field
     * @var string
     */
    private $_DepositDate = 'DepositDate';

    /**
     * mapping for the document folder field
     * @var string
     */
    private $_Folder = 'Folder';

    /**
     * mapping for the document ACL field (a group of bits)
     * @var string
     */
    private $_Acl = 'Acl';

    /**
     * The constructor needs a mandatory parameter with parts:<ul>
     * <li> a category definitions as an array of 
     * <i>cat_id => cat_readable description</i>
     * <li> a field list mapping as an array of
     * <i>innername => databasename</i>
     * </ul>
     * 
     * @param string[] $param (a two item array : array categories and array mapping
     * @param string[] $mapping  list of modified field name
     */
    public function __construct($param) {
        list($categories,$mapping) = $param;
        $this->_prepareMapping($mapping);
        $this->setCreateMissingDir(TRUE);
        parent::__construct();
        $this->setForm($this->_newForm($categories));
        $documentEntity = \Iris\SysConfig\Settings::$DefaultModelLibrary.'TDocuments';
        $this->setEntity($documentEntity::GetEntity());
        $this->setReplaceExistentFile(\Iris\FileSystem\File::MOVEMODE_RENAME);
    }

    /**
     * Recuperate the mapping given to the constructor.
     * 
     * @param string[] $mapping an array of key => field names
     */
    private function _prepareMapping($mapping) {
        foreach ($mapping as $key => $newValue) {
            $key = "_" . $key;
            $this->$key = $newValue;
        }
    }

    /**
     * Skip the overridden method in \Iris\DB\DataBrowser\Upload (which throws an exception)
     * and use \Iris\DB\DataBrowser\_Crud's (Netbeans believes it is static. It is not.)
     * Thank you for the trick Jean-François
     * @see http://blog.lepine.pro/php/objet-acceder-au-grand-parent-en-php
     * 
     * @param mixed $idValues
     * @return type 
     */
    public function delete($idValues) {
        return \Iris\DB\DataBrowser\_Crud::delete($idValues);
    }

    /**
     * Skip the overridden method in \Iris\DB\DataBrowser\Upload (which throws an exception)
     * and use \Iris\DB\DataBrowser\_Crud's (Netbeans believes it is static. It is not.)
     * Thank you for the trick Jean-François
     * @see http://blog.lepine.pro/php/objet-acceder-au-grand-parent-en-php
     * 
     * @param mixed $idValues
     * @return type 
     */
    public function update($idValues) {
        return \Iris\DB\DataBrowser\_Crud::update($idValues);
    }

    /**
     * Overriding the parent method, add the category name at the end of
     * the name of the folder in which store the uploaded file
     * 
     * @param string $fieldName The name of the field used to choice the file
     * @param string[] $uploadedFile The uploaded file metadata (from $_FILES)
     * @param type $formData All the data form the form
     */
    protected function _getFileDir(\Iris\Forms\Elements\FileElement $fileElement) {
        $baseDir = parent::_getFileDir($fileElement);
        $categoryName = "_at_" . $this->_category_id;
        $doc = $this->_sharedData['doc'];
        $category = $doc->$categoryName;
        $categoryFolder = $baseDir . '/' . $category->{$this->_CategoryName};
        $trans = new \Iris\Translation\UTF8($categoryFolder);
        $categoryFolder = $trans->noAccent()->spaceToUnderscore();
        return $categoryFolder;
    }

    /**
     * Prepares a future line in the database for describing the 
     * documents and treats the category. _getFileDir will use it to access
     * the category data following the foreign key (no other way becaus
     * the actual entity for category is not known). A bit tricky...
     * 
     * @param $data a 4 element array shared by 3 method
     * $data = array(
     * 'field' => $fieldName,  // The name of the field used to choose the file
     * 'file'=> $uploadedFile, // The uploaded file metadata (from $_FILES)
     * 'form' => $formData,    // All the data form the form
     * 'name' => NULL          // The final name of the uploaded file
     * ); 
     */
    protected function _preUpload() {

        //// Create new document in database
        $entityName = $this->_EntityName;
        $tDocuments = new $entityName();
        $newDoc = $tDocuments->createRow();
        $newDoc->{$this->_category_id} =
                $this->_sharedData['form'][$this->_category_id];
        // sharing newDoc with other methods
        $this->_sharedData['doc'] = $newDoc;
    }

    /**
     * Finalize then newDoc description
     * 
     * @param $data a 5 element array shared by 3 method
     * $data = array(
     * 'field' => $fieldName,  // The name of the field used to choose the file
     * 'file'=> $uploadedFile, // The uploaded file metadata (from $_FILES)
     * 'form' => $formData,    // All the data form the form
     * 'name' => ...           // The final name of the uploaded file as defined by _processFile
     * 'doc'  =>  ...          // An object describing the file inited by _preUpload 
     * ); 
     */
    protected function _postUpload() {
        $date = new \Iris\Time\Date();

        // recuperates newDoc created by _preUpload
        $newDoc = $this->_sharedData['doc'];
        $newDoc->{$this->_user_id} = \Iris\Users\Identity::GetInstance()->getId();
        $newDoc->{$this->_Description} =
                $this->_sharedData['form'][$this->_Description];
        $newDoc->{$this->_File} = $this->_sharedData['name'];
        $newDoc->{$this->_Validation} = FALSE;
        $newDoc->{$this->_DepositDate} = $date->__toString();
        $fileElement = $this->_form->getComponent($this->_sharedData['field']);
        // here we only need the base directory (not the category): return to Upload Method
        $newDoc->{$this->_Folder} = \Iris\DB\DataBrowser\Upload::_getFileDir($fileElement) . '/';
        $newDoc->save();
        return;
    }

    /**
     *
     * @param string[] $categories as received by the constructor
     * @return \Iris\Forms\_Form 
     */
    protected function _newForm($categories) {
        // form creation (with formUpload as a template)
        $ff = \Iris\Forms\_FormFactory::GetFormFactory();
        //@todo : formUpload is called from _Crud class: it must not be a helper 
        $form = \Iris\controllers\helpers\_ControllerHelper::HelperCall('formUpload', array(),\NULL);
        // need to add category link and description
        $cat = $form->createSelect($this->_category_id)
                ->setLabel('Category:')
                ->setTitle('Category which the document belongs')
                ->addTo($form->getComponent('_before_'))
                ->addOptions($categories);
        $desc = $ff->createText($this->_Description)
                ->setLabel('Document:')
                ->setTitle("Une short description for the document")
                ->addTo($form->getComponent('_before_'))
                ->addValidator('Required');
        // all other fields will be automatically filled
        // user_id , Description, File, Status, DepositDate, Folder, Acl 
        return $form;
    }

}

