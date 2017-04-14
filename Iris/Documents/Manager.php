<?php

namespace Iris\Documents;

use \Iris\Exceptions as _IEx_;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
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

    /**
     *
     * @var string
     * @deprecated since sept 2016
     */
//    protected static $_TutorialDir;

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

    /**
     * A list of standard mime names corresponding with classical extensions
     * 
     * @var string
     */
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
        self::$_Instance = $this;
    }

    /**
     * 
     * @param type $save
     * @param type $params
     * @return type
     */
    public function getFile($save, $params) {
        $protpriv = array_shift($params);
        $baseDirectory = \Iris\SysConfig\Settings::$DataFolder;
        if ($protpriv == 'protected') {
            $pathName = sprintf("%s/protected/%s", $baseDirectory, implode('/', $params));
        }
        // get Magic Number for control in private area
        else {
            $externalSecurity = array_shift($params);
            $internalSecurity = $_SESSION['FileManagerToken'];

            if ($internalSecurity != $externalSecurity) {
                return self::BADNUMBER;
            }
            $pathName = sprintf("%s/private/%s", $baseDirectory, implode('/', $params));
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

    /**
     * 
     * @param type $params
     * @return type
     */
    public function getResource($params) {
        $pathName = sprintf("%s/%s/ILO/%s", IRIS_ROOT_PATH, IRIS_LIBRARY, implode('/', $params));
        $name = basename($pathName);
        $mime = $this->_getMime($name);
        if (file_exists($pathName)) {
            return $this->_execRead(FALSE, $pathName, $mime);
        }
        else {
            return self::NOTFOUND;
        }
    }

    /**
     * An old method in relation with tutorials
     * 
     * @param type $params
     * @return type
     * @deprecated since version 2016
     */
//    public function getTutorial($params) {
//        $tutorialDir = self::$_TutorialDir;
//        $pathName = sprintf("%s/%s/%s", IRIS_ROOT_PATH, $tutorialDir, implode('/', $params));
//        $name = basename($pathName);
//        $mime = $this->_getMime($name);
//        if (file_exists($pathName)) {
//            return $this->_execRead(FALSE, $pathName, $mime);
//        }
//        else {
//            return self::NOTFOUND;
//        }
//    }

    /**
     * 
     * @param type $fileName
     * @return string
     */
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

    /**
     * 
     * @param type $directory
     * @deprecated since sept 2016
     */
//    public static function setTutorialDir($directory){
//        self::$_TutorialDir = $directory;
//    }
}
