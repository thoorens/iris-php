<?php

namespace Iris\Documents;

use \Iris\Exceptions as _IEx_;

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
 *  A document manager for creating documents on the fly.
 * 
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ *
 */
class Manager {
    const GOTIT = 1;
    const BADNUMBER = 2;
    const NOTFOUND = 3;

    protected static $_TutorialDir;
    
    /**
     *
     * @var Manager
     */
    protected static $_Instance = NULL;

    /**
     *
     * @var string
     */
    protected $_baseDirectory;
    protected $_mimeTypes = array(
        'pdf' => 'application/pdf',
        'ps' => 'application/postscript',
        'dtd' => 'application/xml-dtd',
        'xop' => 'application/xop+xml',
        'zip' => 'application/zip',
        'gzip' => 'application/x-gzip',
        'mp4' => 'audio/mp4',
        'mp3' => 'audio/mpeg',
        'ogg' => 'audio/ogg',
        //'' => 'audio/vnd.rn-realaudio',
        'wav' => 'audio/vnd.wave',
        'gif' => 'image/gif',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'jfif' => 'image/jpeg',
        'png' => 'image/png',
        'svg' => 'image/svg+xml',
        'tif' => 'image/tiff',
        'ico' => 'image/vnd.microsoft.icon',
        'css' => 'text/css',
        'csv' => 'text/csv',
        'html' => 'text/html',
        'js' => 'text/javascript',
        'txt' => 'text/plain',
        'vcard' => 'text/vcard',
        'xml' => 'text/xml',
        'mpeg' => 'video/mpeg',
        'mp4' => 'video/mp4',
        'wmv' => 'video/x-ms-wmv',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'odp' => 'application/vnd.oasis.opendocument.presentation',
        'xls' => 'application/vnd.ms-excel',
        //'' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'doc' => 'application/msword',
        'xul' => 'application/vnd.mozilla.xul+xml',
        'dvi' => 'application/x-dvi',
        'tex' => 'application/x-latex',
        'ttf' => 'application/x-font-ttf',
        'flex' => 'application/x-shockwave-flash',
        'rar' => 'application/x-rar',
        'tar' => 'application/x-tar',
        'deb' => 'application/x-deb',
        'rpm' => 'application/x-rpm'
    );

    /**
     *
     * @return Manager
     */
    public static function GetInstance() {
        if (is_null(self::$_Instance)) {
// self registering
            new self();
        }
        return self::$_Instance;
    }

    /**
     * Poor man singleton implementation
     */
    public function __construct() {
        if (!is_null(self::$_Instance)) {
            throw new _IEx_\FileException('One Document File Manager may be active at a time.');
        }
        $this->_baseDirectory = \Iris\SysConfig\Settings::$DataFolder;
        self::$_Instance = $this;
    }

    public function getFile($save, $params) {
        $pubpri = array_shift($params);
        if ($pubpri == 'protected') {
            $pathName = sprintf("%s/protected/%s", $this->_baseDirectory, implode('/', $params));
        }
        // get Magic Number for control in private area
        else {
            $externalSecurity = array_shift($params);
            $internalSecurity = $_SESSION['FileManagerToken'];

            if ($internalSecurity != $externalSecurity) {
                return self::BADNUMBER;
            }
            $pathName = sprintf("%s/private/%s", $this->_baseDirectory, implode('/', $params));
        }
        $name = basename($pathName);
        $mime = $this->_getMime($name);
        if (file_exists($pathName)) {
            return $this->_execRead($save, $pathName, $mime);
        }
        ///if (\Iris\Engine\Mode::IsProduction()) {
            return self::NOTFOUND;
        ///}
    }

    public function getResource($params) {
        $pathName = sprintf("%s/library/ILO/%s", IRIS_ROOT_PATH, implode('/', $params));
        $name = basename($pathName);
        $mime = $this->_getMime($name);
        if (file_exists($pathName)) {
            return $this->_execRead(FALSE, $pathName, $mime);
        }
        else {
            return self::NOTFOUND;
        }
    }

    
    public function getTutorial($params) {
        $tutorialDir = self::$_TutorialDir;
        $pathName = sprintf("%s/%s/%s", IRIS_ROOT_PATH, $tutorialDir, implode('/', $params));
        $name = basename($pathName);
        $mime = $this->_getMime($name);
        if (file_exists($pathName)) {
            return $this->_execRead(FALSE, $pathName, $mime);
        }
        else {
            return self::NOTFOUND;
        }
    }
    protected function _getMime($fileName) {
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        if (isset($this->_mimeTypes[$ext])) {
            return $this->_mimeTypes[$ext];
        }
        else {
            return 'text/plain';
        }
    }

    protected function _execRead($save, $pathName, $mime) {
        header("content-type:$mime");
        header("Content-Length: " . filesize($pathName));
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 26 Jul 2012 07:00:00 GMT"); // Date in the past
        if ($save) {
            header('Content-Disposition: attachment; filename="' . basename($pathName) . '"');
        }
        try {
            readfile($pathName);
        }
        catch (Exception $ex) {
            iris_debug($ex->getTraceAsString());
        }
        return self::GOTIT;
    }

    public static function setTutorialDir($directory){
        self::$_TutorialDir = $directory;
    }
    
}