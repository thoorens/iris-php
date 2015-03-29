<?php

namespace Iris\Documents\DataBrowser;

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
 * Upload is a special form of Crud (it can be used as it is)
 * for uploading one or more files. 
 * For more features (as user id, category, date, status and availability, 
 * use \Iris\Documents\ExtendeUpload instead, a subclass of this one.
 * The classical create/read/update/delete methods are desactivated (but
 * may be overridden in subclasses).
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Upload extends \Iris\DB\DataBrowser\_Crud {

    /**
     * Default destination directory
     * 
     * @var string
     */
    protected $_destinationDirectory = '/';

    /**
     * What to do when the target directory is missing
     * @var boolean 
     */
    protected $_createMissingDir = FALSE;

    /**
     * What to do if the target file exists (constants defined in \Iris\Filesystem\File 
     * @var int
     */
    protected $_replaceExistentFile;

    /**
     * A 4 element array shared by different methods (it may grow in subclasses
     * All data are meant to be significant only during the moving of an uploaded file
     * 
     * @var mixed[]
     */
    protected $_sharedData = array(
        'field' => NULL, // The name of the field used to choose the file
        'file' => NULL, // The uploaded file metadata (from $_FILES)
        'form' => NULL, // All the data form the form
        'name' => NULL // The final name of the uploaded file
    );

    /**
     * This parameters may be used in subclasses
     * 
     * @param mixed $param 
     */
    public function __construct($param = \NULL) {
        $this->setReplaceExistentFile(\Iris\FileSystem\File::MOVEMODE_REPLACE);
        parent::__construct($param);
    }

    /**
     * Realizes the upload (analyzing all parameters)
     * 
     * @return mixed 
     */
    public function upload() {

        if (\Iris\Engine\Superglobal::GetServer('REQUEST_METHOD') == 'POST') {
            $formData = \Iris\Engine\Superglobal::GetPost();
            if ($this->_form->isValid($formData)) {
                // there may be more than one file in the form
                foreach ($_FILES as $fieldName => $uploadedFile) {
                    $this->_sharedData['field'] = $fieldName;
                    $this->_sharedData['file'] = $uploadedFile;
                    $this->_sharedData['form'] = $formData;
                    $this->_preUpload();
                    $this->_processFile();
                    $this->_postUpload();
                }
                return self::RC_END;
            }
            return NULL;
        }
    }

    /**
     * Desactivated method
     * @ignore
     */
    public function create($type = NULL, $data = null) {
        $this->_desactivated('create');
    }

    /**
     * Desactivated method (\Iris\Documents\ExtendedUpload override it)
     * @ignore
     */
//    public function delete($idValues) {
//        $this->_desactivated('delete');
//    }

    /**
     * Desactivated method
     * @ignore
     */
    public function read($conditions) {
        $this->_desactivated('read');
    }

    /**
     * Desactivated method (\Iris\Documents\ExtendedUpload override it)
     * @ignore
     */
    public function update($idValues) {
        $this->_desactivated('update');
    }

    /**
     * Prevents the 4 above methods to function
     * @ignore
     */
    private function _desactivated($function) {
        throw new \Iris\Exceptions\DBException("Upload does not support $function method");
    }

    /**
     * This is a callback for any processing to be done before the 
     * file recuperation and renaming such as backing up old files
     * or getting more info for the _processFile
     * 
     */
    protected function _preUpload() {
        
    }

    /**
     * Each file is moved to its destination directory and is given a 
     * name 
     * @todo Verify use of move_uploaded_file
     * 
     */
    protected function _processFile() {
        $uploadedFile = $this->_sharedData['file'];
        // get temp file path
        $filePath = $uploadedFile['tmp_name'];
        $tempFile = new \Iris\FileSystem\File(basename($filePath), dirname($filePath));
        $tempFile->setCreateMissingDir($this->_createMissingDir);
        $newFile = $this->_getFilePath();

        //die("Mode: ".$this->_replaceExistentFile);
        // why not move_uploaded_file() ?
        $tempFile->moveToFile($newFile, $this->_replaceExistentFile);
        $this->_sharedData['name'] = $tempFile->getBaseName();
    }

    /**
     * This is a callback for any processing to be done after the 
     * file recuperation and renaming such as updating a database
     * 
     */
    protected function _postUpload() {
        
    }

    /**
     * 
     * @return \Iris\FileSystem\File
     */
    protected function _getFilePath() {
        $fileElement = $this->_form->getComponent($this->_sharedData['field']);
        $newPath = $this->_getFileDir($fileElement);
        $newFileName = $this->_getFileName($fileElement);
        return new \Iris\FileSystem\File($newFileName, $newPath);
    }

    /**
     * Final directory for the upload file:<ul>
     * <li> the directory by fileElement->setSpecificDirectory()
     * <li> the common directory set by $this->setSpecificDirectory()
     * <li> /data (by default)
     * </ul>
     * 
     * @param \Iris\Forms\Elements\FileElement $fileElement
     * @return type 
     */
    protected function _getFileDir(\Iris\Forms\Elements\FileElement $fileElement) {
        if (is_null($fileElement->getSpecificDirectory())) {
            $newPath = \Iris\SysConfig\Settings::GetDataFolder() . $this->_destinationDirectory;
        }
        else {
            $newPath = IRIS_ROOT_PATH . '/' . $fileElement->getSpecificDirectory();
        }
        return $newPath;
    }

    /**
     * Final file name for the uploaded file : the original name
     * can be replaced by the one optionally set by setProposedFileName applied to
     * the fileElement of the form.
     * This method may be overridden to offer other name strategy
     * 
     * @param string $fileName
     * @param \Iris\Forms\Elements\FileElement $fileElement
     * @return string 
     */
    protected function _getFileName(\Iris\Forms\Elements\FileElement $fileElement) {
        $fileName = $this->_sharedData['file']['name'];
        if (!is_null($fileElement->getProposedFileName())) {
            $fileName = $fileElement->getProposedFileName();
        }
        $trans = new \Iris\Translation\UTF8($fileName);
        $fileName2 = $trans->spaceToUnderscore()->noAccent()->__toString();
        //die($fileName." - ".$fileName2);
        return $fileName2;
    }

    /**
     * Set the common destination directory for all uploaded files
     * It can be overridden by setSpecificDirectory() method applied to a FileElement
     *  
     * @param type $value 
     */
    public function setDestinationDirectory($value) {
        $this->_destinationDirectory = $value;
    }

    /**
     * How to react when a file exists
     * 
     * @param int $value Different way to react (see \Iris\Filesystem\File MOVEMODE_* constants) 
     */
    public function setReplaceExistentFile($value) {
        $this->_replaceExistentFile = $value;
    }

    /**
     * Modify the way to handle missing directories
     * 
     * @param boolean $createDirectory 
     */
    public function setCreateMissingDir($createDirectory = TRUE) {
        $this->_createMissingDir = $createDirectory;
    }

    protected function _initObjects($mode, $id = \NULL) {
        
    }

}

