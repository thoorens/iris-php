<?php

namespace Iris\OS;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * An abstract OS implementation. Allmost all methods are here.
 * Subclass Unix adds one more, Windows subclass adds various methods
 * depending on version.
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _OS {// implements \Iris\Design\iSingleton 

    const MKDIR = 1;
    const COPY = 2;
    const UNLINK = 3;
    const RENAME = 4;
    const RMDIR = 5;
    const LINK = 6;
    const SYMLINK = 7;
    const TOUCH = 8;
    const PUT = 9;
    const GET = 10;
    const UNKNOWN = 'UNKNOWN';
    const LINUX = 'LINUX';
    const WIN10 = 'WINDOWS 10';
    const WIN8 = 'WINDOWS 8';
    const WIN7 = 'WINDOWS 7';
    const WINVI = 'WINDOWS VISTA';
    const WINXP = 'WINDOWS XP';
    const WINNT = 'WINDOWS NT';

    private static $_OS = [
        self::UNKNOWN,
        self::LINUX,
        self::WIN10,
        self::WIN8,
        self::WIN7,
        self::WINVI,
        self::WINXP,
        self::WINNT,
    ];
    public $tabLevel = 0;
    protected $_version;

    /**
     * An array containing all the format for displaying verbose messages
     * 
     * @var string[]
     */
    protected $_format = [];

    /**
     *
     * @var Unix
     */
    protected static $_Instance = NULL;

    /**
     * return the instance of the active OS
     * @return Unix (or maybe Windows)
     */
    public static function GetInstance() {
        if (self::$_Instance == NULL) {
            switch (self::_DetectOS()) {
                case self::UNKNOWN:
                case self::LINUX:
                    self::$_Instance = new Unix();
                    break;
                case self::WIN10:
                case self::WIN8:
                case self::WIN7:
                    self::$_Instance = new Windows();
                    break;
                case self::WINVI:
                case self::WINNT:
                case self::WINXP:
                    self::$_Instance = new XP();
                    break;
            }
        }
        return self::$_Instance;
    }

    /**
     * Returns the number corresponding to full permission to user only
     * 
     * @return int
     * @todo : could be changed for management through Admin
     */
    public static function GetPrivateMod() {
        return 0755;
    }

    /**
     * Returns the number corresponding to full permission
     * 
     * @return int
     * @todo : could be changed for management through Admin
     */
    public static function GetPublicMod() {
        return 0777;
    }

    /**
     * TRUE if verbosity on
     * @var boolean  
     */
    protected $_verbose = FALSE;

    /**
     * a copy of verbosity state when simulation starts
     * @var boolean
     */
    protected $_oldVerbose = FALSE;

    /**
     * TRUE if simulation is on
     * @var boolean
     */
    protected $_simulate = FALSE;

    /**
     * A String to indentificate OS
     * @var type 
     */
    public static $OSName;

    /**
     * The class behaves as a singleton (not marked by \Iris\Design\iSingleton
     * to minimize dependencies in CLI)
     */
    protected function __construct() {
        $this->_version = self::_DetectOS();
        $this->_format[self::MKDIR] = "Creating directory %s\n";
        $this->_format[self::COPY] = "Copying %s to %s\n";
        $this->_format[self::UNLINK] = "Removing file %s\n";
        $this->_format[self::RENAME] = "Moving/renaming %s to %s\n";
        $this->_format[self::RMDIR] = "Removing directory %s\n";
        $this->_format[self::LINK] = "Creating a link from %s to %s \n";
        $this->_format[self::SYMLINK] = "Creating a symbolic link for %s as %s\n";
        $this->_format[self::TOUCH] = "Creating file %s\n";
        $this->_format[self::PUT] = "Putting data in file %s\n";
        $this->_format[self::GET] = "Getting data from file %s\n";
    }

    /**
     * Gives everybody a complete access to a file
     * 
     * @param string $fileName
     */
    public function fullPermission($fileName) {
        \chmod($fileName, 0777);
    }

    /**
     * A substitute to standard mkdir with verbose and simulation modes
     * 
     * @param string $pathname the dir name
     * @param int $mode value of chmod for the file (default octal 755) 
     * @param boolean $recursive enable recursive creation of complexe path
     */
    public function mkDir($pathname, $mode = 0755, $recursive = \NULL) {
        $this->_verbose and $this->_echo(self::MKDIR, $pathname);
        $this->_simulate or mkdir($pathname, $mode, $recursive);
    }

    /**
     * A substitute to standard rmdir with verbose and simulation modes
     * 
     * @param string $dirname the dir name
     */
    public function rmDir($dirname) {
        $this->_verbose and $this->_echo(self::RMDIR, $dirname, $this->tabLevel);
        $this->_simulate or rmdir($dirname);
    }

    /**
     * A substitute to standard copy with verbose and simulation modes
     * 
     * @param string $source
     * @param string $dest
     */
    public function copy($source, $dest) {
        $this->_verbose and $this->_echo(self::COPY, array($source, $dest));
        $this->_simulate or copy($source, $dest);
    }

    /**
     * A substitute to standard symlink with verbose and simulation modes: 
     * creates a symbolic link
     * 
     * @param string $target
     * @param string $link 
     */
    public function symlink($target, $link) {
        $this->_verbose and $this->_echo(self::SYMLINK, array($target, $link));
        $this->_simulate or symlink($target, $link);
    }

    /**
     * A substitute to standard link with verbose and simulation modes
     * creates a hard link
     * @param string $from_path
     * @param string $to_path 
     */
    public function link($from_path, $to_path) {
        $this->_verbose and $this->_echo(self::LINK, array($from_path, $to_path));
        $this->_simulate or link($from_path, $to_path);
    }

    /**
     * A substitute to standard copy with verbose and simulation modes
     * 
     * @param string $oldname
     * @param string $newname
     */
    public function rename($oldname, $newname) {
        rename($oldname, $newname);
    }

    /**
     * A substitute to standard unlink with verbose and simulation modes
     * Delete a file
     * 
     * @param string $filename
     */
    public function unlink($filename) {
        $this->_verbose and $this->_echo(self::UNLINK, $filename, $this->tabLevel);
        $this->_simulate or unlink($filename);
    }

    /**
     * A substitute to standard touch with verbose and simulation modes
     * Creates a new file
     * 
     * @param string $filename
     * @param int $time
     * @param int $atime 
     */
    public function touch($filename, $time = NULL, $atime = NULL) {
        $this->_verbose and $this->_echo(self::TOUCH, $filename);
        $this->_simulate or touch($filename, $time, $atime);
    }

    /**
     * A substitute to standard file_put_content with verbose and simulation modes
     * Fills a file with data
     * 
     * @param string $filename
     * @param mixed $data
     * @param int $flags
     */
    public function file_put_contents($filename, $data, $flags = NULL) {
        $this->_verbose and $this->_echo(self::PUT, $filename);
        $this->_simulate or file_put_contents($filename, $data, $flags);
    }

    public function file_get_contents($filename, $flags = \NULL) {
        $this->_verbose and $this->_echo(self::GET, $filename);
        if ($this->_simulate) {
            return "False data";
        }
        else {
            return file_get_contents($filename, $flags);
        }
    }

    /**
     * Creates a file using a template with some fields to replace. An array
     * with fields and matching values must be given in the form
     *     array(field1=>value1, field2=>value2...)
     * The replacement may occurs various time and the indexes and values are
     * treated as regular expressions
     * 
     * @param string $source the path to the original template file
     * @param string $destination the path to the new file
     * @param mixed[] $replacement an associative array with the fields and values 
     */
    public function createFromTemplate($source, $destination, $replacement = []) {
        if (strpos($source, '%') === \FALSE) {
            $sourceFile = $source;
        }
        else{
            $sourceFile = sprintf($source, 'Myfiles');
            if (!file_exists($sourceFile)) {
                $sourceFile = sprintf($source, 'Files');
            }
        }
        $text = $this->file_get_contents($sourceFile);
        foreach ($replacement as $from => $to) {
            $text = preg_replace("/$from/", "$to", $text);
        }
        $this->file_put_contents($destination, $text);
    }

    /**
     * This function has to determine if the running OS is Windows
     * 
     * @staticvar boolean $Answer
     * @return string 
     */
    protected static function _DetectOS() {
        static $osName = \NULL;
        if (is_null($osName)) {
            $uname = php_uname('a');
            $osName = self::UNKNOWN;
            foreach (self::$_OS as $os) {
                if (strpos(strtoupper($uname), $os) !== \FALSE) {
                    $osName = $os;
                    break;
                }
            }
        }
        self::$OSName = $osName;
        return $osName;
    }

    /**
     * Activates the verbose mode of the operations
     * 
     * @param boolean $verbose 
     */
    public function setVerbose($verbose = TRUE) {
        $this->_verbose = $verbose;
        $this->_oldVerbose = $verbose;
    }

    /**
     * Activates the simulation of the operation (with verbosity)
     * 
     * @param boolean $simulate 
     */
    public function setSimulate($simulate = TRUE) {
        $this->_simulate = $simulate;
        if ($simulate) {
            $this->_oldVerbose = $this->_verbose;
            $this->_verbose = TRUE;
        }
        else {
            $this->_verbose = $this->_oldVerbose;
        }
    }

    /**
     * Display a verbose message of the current operation
     * @param int $messageType Number of the format to use
     * @param string/array $value  parameters (1 or 2)
     */
    protected function _echo($messageType, $values, $level = 0) {
        $number = -$level * 4;
        if ($number) {
            echo(sprintf("%${number}s", ' '));
        }
        //print $level;
        if (is_string($values)) {
            $values = array($values);
        }
        $values[] = $values[] = '';
        echo $this->_(sprintf($this->_format[$messageType], $values[0], $values[1]));
    }

    /**
     * Future possible extension (translation)
     * @param string $message
     * @return string 
     */
    protected function _($message) {
        return $message;
    }

    public function modTabLevel($num) {
        $this->tabLevel += $num;
    }

    /**
     * Get user home directory 
     * 
     * @return string
     */
    public abstract function getUserHomeDirectory();

    protected function shellvar($var) {
        return str_replace("\n", "", shell_exec("echo %$var%"));
    }

}
