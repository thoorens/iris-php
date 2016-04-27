<?php

namespace Iris\FileSystem;

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
 * File management with security, backup and exceptions
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class File {
    /**
     * In case of existing file : replace it
     */

    const MOVEMODE_REPLACE = 1;
    /**
     * In case of existing file : increment new filename with 01 02 ...
     */
    const MOVEMODE_RENAME = 2;
    /**
     * In case of existing file : throw an exception
     */
    const MOVEMODE_ERROR = 3;
    /**
     * In case of existing file : backup old file and remove old backup
     */
    const MOVEMODE_BACKUP = 4;
    /**
     * In case of existing file : backup old files conserving history
     */
    const MOVEMODE_HISTORY = 5;

    /**
     * The dir part of the full name
     * @var string
     */
    protected $_dirName;

    /**
     * The file name (with extension)
     * @var string
     */
    protected $_baseName;

    /**
     * The number of backup files to keep (used with MOVEMODE_HISTORY
     * @var int
     */
    protected $_BUhistoryLevel = 5;

    /**
     * Must the backup files keep the extension
     * @var boolean 
     */
    protected $_BUKeepExtension = FALSE;

    /**
     * A format string for the backup mark (by default produce .b02 .b03 ...)
     * Must contain %d to manage a number.
     * @var string 
     */
    protected $_BUPattern = ".b%02d";

    /**
     * The mark for the first backup file (by default .bak)
     */
    protected $_BUFirstExtension = ".bak";

    /**
     * What to do if target directory does not exist
     * @var boolean
     */
    protected $_CreateMissingDir = FALSE;

    /**
     * Constructor for a File object
     * 
     * @param string $baseName 
     * @param string $dirName (by default: script file dir) 
     */
    public function __construct($baseName, $dirName = NULL) {
        $this->_baseName = $baseName;
        if (is_null($dirName)) {
            $this->_dirName = dirname(\Iris\Engine\Superglobal::GetServer('SCRIPT_FILENAME'));
        }
        else {
            $this->_dirName = $dirName;
        }
    }

    /**
     * Returns the fullpath of the current file
     * @return string
     */
    public function fullPath() {
        return $this->getPathOther($this->_baseName, $this->_dirName);
    }

    /**
     * Returns a full name based on directory name and base name
     * 
     * @param string $baseName the base name of the file
     * @param string $dirName the directory name of the file (if NULL the same as current file)
     * @return string
     */
    public function getPathOther($baseName, $dirName=NULL) {
        if (is_null($dirName)) {
            $dirName = $this->_dirname;
        }
        return "$dirName/$baseName";
    }

    /**
     * Transforms the current file into a backup file (by changing its name)
     * All preceding backups files are incremented and the file beyond $max
     * is deleted
     * 
     * @param int $max : number of backup files (from .bak to .bmax)
     * @param boolean $rmExt : if true remove extension
     */
    public function backup($max = 1, $rmExt = TRUE) {
        if ($rmExt) {
            $ext = "." . pathinfo($this->_baseName, PATHINFO_EXTENSION);
            $baseName = basename($this->_baseName, $ext);
        }
        else {
            $ext = '';
            $baseName = $this->_baseName;
        }
        for ($num = $max - 1; $num >= 1; $num--) {
            $currBU = $num == 1 ? $this->_BUFirstExtension : sprintf($this->_BUPattern, $num);
            $nextBU = sprintf($this->_BUPattern, $num + 1);
            if ($this->existsOther($baseName . $currBU . $ext)) {
                $this->renameOther($baseName . $currBU . $ext, $baseName . $nextBU . $ext, self::MOVEMODE_REPLACE);
            }
        }
        if ($this->exists()) {
            $this->rename($baseName . ".bak" . $ext, self::MOVEMODE_REPLACE);
        }
    }

    /**
     * 
     * @param string $basename
     * @param string $dirname
     * @return boolean 
     */
    public function exists($basename = NULL, $dirname = NULL) {
        if (is_null($basename)) {
            $basename = $this->_baseName;
        }
        if (is_null($dirname)) {
            $dirname = $this->_dirName;
        }
        return file_exists($dirname . '/' . $basename);
    }

    /**
     * Strictly renames a file (it stays where it was)
     * 
     * @param string $newName : new name of the file
     * @param int $errorMode what to do in case of existing file
     * @exception \Iris\Exceptions\FileException
     */
    public function rename($newName, $errorMode = self::MOVEMODE_ERROR) {
        return $this->move($this->_dirName, $newName, $errorMode);
    }

    /**
     * Moves a file
     * @param type $newDirname
     * @param type $newBasename
     * @param int $errorMode what to do in case of existing file
     * @exception \Iris\Exceptions\FileException
     */
    public function move($newDirname, $newBasename=NULL, $errorMode = self::MOVEMODE_RENAME) {

        // if not newBaseName specified, the file is only moved without renaming
        if (is_null($newBasename)) {
            $newBasename = $this->_baseName;
        }
        $oldFile = $this->getPathOther($this->_baseName, $this->_dirName);
        $newFile = $this->getPathOther($newBasename, $newDirname);
        $nf = $this->_dolink($oldFile, $newFile, TRUE, $errorMode);
        $this->_baseName = $nf->_baseName;
        $this->_dirName = $nf->_dirName;
    }

    /**
     * Move the current file to another using an File object parameter
     * 
     * @param self $newFile
     * @param type $errorMode  what to do if target file exists
     */
    public function moveToFile(self $newFile, $errorMode = self::MOVEMODE_RENAME) {
        $newBasename = $newFile->_baseName;
        $newDirname = $newFile->_dirName;
        $this->move($newDirname, $newBasename, $errorMode);
    }

    /**
     * Look for a non existing file name in the directory for the current file
     * by incrementing numbers: eg. foo.bar becomes foo01.bar or foo02.bar 
     * 
     * $file = new \Iris\FileSystem\File('foo.bar','/tmp');
     * $file->touch(); // creates foo.bar
     * $file->noDuplicate();
     * $file->touch(); // creates foo01.bar
     * 
     */
    public function noDuplicate() {
        $this->_baseName = $this->noDuplicateOther($this->_baseName);
    }

    /**
     * Creates a new file (or reset metadata of an existant one)
     * 
     * @param string $newName 
     * @todo try catch instead of @
     */
    public function touch($newName) {
        $success = @touch($this->fullPath());
        if (!$success) {
            $messages = error_get_last();
            throw new \Iris\Exceptions\FileException($messages['message']);
        }
    }

    /**
     * Creates a link for the current file (if OS permits it) otherwise
     * it create a copy (old Windows or files on different devices)
     * 
     * @param string $newDirname the directory where to create the link
     * @param string $newBaseName the new basename of the link (fac: same as current)
     * @param int $errorMode what to do if target file exists 
     */
    public function linkFile($newDirname, $newBaseName=NULL, $errorMode = self::MOVEMODE_ERROR) {
        if(is_null($newBaseName)){
            $newBaseName = $this->_baseName;
        }
        $currentPath = $this->fullPath();
        $newPath = $this->getPathOther($newBaseName, $newDirname);
        $this->_dolink($currentPath, $newPath, FALSE, $errorMode, false);
    }

    /**
     *
     * @param string $oldPath path to the existing file
     * @param string $newPath path to the new link
     * @param booelan $unlink if true, the original is deleted
     * @param int $errorMode what to do if target file exists
     * @return File 
     */
    protected function _dolink($oldPath, $newPath, $unlink, $errorMode) {
        //// if necessary make target directory
        $newDirname = dirname($newPath);
        if (!file_exists($newDirname)) {
            if ($this->_CreateMissingDir) {
                mkdir($newDirname);
            }
            else {
                throw new \Iris\Exceptions\FileException('Unexisting target dir');
            }
        }
        // what to do if target file exists
        if (file_exists($newPath)) {
            switch ($errorMode) {
                case self::MOVEMODE_BACKUP:
                    $this->backup(1);
                    break;
                case self::MOVEMODE_HISTORY:
                    $this->backup($this->_BUhistoryLevel);
                    break;
                case self::MOVEMODE_ERROR:
                    throw new \Iris\Exceptions\FileException('Existing file');
                    break;
                case self::MOVEMODE_RENAME:
                    $newFile = new File(basename($newPath), dirname($newPath));
                    $newFile->noDuplicate();
                    $newPath = $newFile->fullPath();
                    break;
                case self::MOVEMODE_REPLACE:
                    unlink($newPath);
                    break;
            }
        }
        // @todo : try catch
        @copy($oldPath, $newPath);
        if (error_get_last()) {
            if (!@copy($oldPath, $newPath)) {
                $messages = error_get_last();
                echo "<pre>";
                throw new \Iris\Exceptions\FileException($messages['message']);
            }
        }
        if ($unlink) {
            unlink($oldPath);
            $link = $this;
        }
        return new File(\basename($newPath), \dirname($newPath));
    }

    public function unlink() {
        unlink($this->fullPath());
    }

    /**
     * Returns the directory part of the file path
     * @return string 
     */
    public function getDirName() {
        return $this->_dirName;
    }

    /**
     * Returns the file part of the file path
     * 
     * @return string
     */
    public function getBaseName() {
        return $this->_baseName;
    }

    /**
     * Get the current file extension (without dot)
     * @return string
     */
    public function getExtension() {
        return \pathinfo($this->_baseName, PATHINFO_EXTENSION);
    }

    /**
     * Accessor for the level of backup file history (default to 1)
     * @param int $historyLevel 
     */
    public function setHistoryLevel($historyLevel) {
        $this->_BUhistoryLevel = $historyLevel;
    }

    /**
     * Permit to control the (non)creation of non existing dir
     * @param boolean $value 
     */
    public function setCreateMissingDir($value = TRUE) {
        $this->_CreateMissingDir = $value;
    }

    /**
     * Define the string for first bakup file and a format for the next ones
     * (a printf format containing an %d code e.g. bak%02d)
     *  
     * @param string $first string for first bu file
     * @param string $next format for other files
     */
    public function setBUMark($first = NULL, $next = NULL) {
        if (!is_null($first)) {
            $this->_BUFirstExtension = $first;
        }
        if (!is_null($next)) {
            $this->_BUPattern = $next;
        }
    }

//    private function _log($message) {
//        \Iris\Engine\Log::Debug($message, \Iris\Engine\Debug::FILE, 'File');
//    }

    /**
     * A convenient way of renaming a file in the same directory as the current
     * + homonym management
     * 
     * @param string $oldName Old name the of the file
     * @param string $newName New name of the file
     */
    public function renameOther($oldName, $newName, $errorMode = self::MOVEMODE_ERROR) {
        $this->moveOther($oldName, $this->_dirName, $newName, $errorMode);
    }

    /**
     * Move a file from the same directory as current file
     * 
     * @param string $oldName present name of the file
     * @param string $newDir target directory
     * @param string $newName new name of the file (if NULL, no change)
     * @param int $errorMode  what to do if target file exists
     */
    public function moveOther($oldName, $newDir, $newName = NULL, $errorMode = self::MOVEMODE_ERROR) {
        $oldDir = $this->_dirName;
        if (is_null($newName)) {
            $newName = $oldName;
        }
        $oldFile = new self($oldName, $oldDir);
        $newFile = new self($newName, $newDir);
        $oldFile->moveToFile($newFile, $errorMode);
    }

    /**
     * Tests the existence of a file in the same directory as the current file
     * @paramstring $fileName
     * @return boolean
     */
    public function existsOther($fileName) {
        return file_exists($this->_dirName . "/" . $fileName);
    }

    /**
     * Look for a non existing file name in the directory starting from a proposal
     * by incrementing numbers: eg. foo.bar becomes foo01.bar or foo02.bar
     * 
     * @param string $baseName the name of the file to test
     * @return string
     */
    protected function noDuplicateOther($baseName) {
        $num = 1;
        $ext = pathinfo($baseName, PATHINFO_EXTENSION);
        $base = pathinfo($baseName, PATHINFO_FILENAME);
        while ($this->existsOther($baseName)) {
            $baseName = sprintf("$base%02d.%s", $num++, $ext);
        }
        return $baseName;
    }

}


