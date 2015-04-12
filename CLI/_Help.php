<?php

namespace CLI;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Common part for the localized help classes
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $/**
 * Common part for the localized help classes
 *
 */
abstract class _Help {

    const NOPATH = 1;
    const DEFAULTLANGUAGE = 'English';

    protected $_functions;

    function __construct($functions) {
        $this->_functions = $functions;
    }

    public function display($command) {
        $command = $command === FALSE ? 'help' : $command;
        switch ($command) {
            case 'help':case 'h':case'--':
                $this->_general();
                break;

            case 'createproject':case 'c':
                $this->_createProject();
                break;

            case 'alterproject':case'a':
                $this->_alterProject();
                break;

            case 'removeproject':case'r':
                $this->_removeProject();
                break;

            case 'show':case's':
                $this->_show();
                break;

            case 'url':case 'u':
                $this->_url();
                break;

            case 'mkcore':case 'k':
                $this->_mkCore();
                break;

            case 'searchcore':case 'K':
                $this->_searchCore();
                break;

            case 'database': case 'B':
                $this->_database();
                break;

            case 'selectbase': case 'b':
                $this->_selectBase();
                break;

            case 'otherdb': case'O':
                $this->_otherDB();
                break;

            case 'entitygenerate': case 'e':
                $this->_entityGenerate();
                break;


            case 'mkdbini': case 'I':
                $this->_makeDbIni();
                break;

            /** ==============================================================================================
             *
             */
            default:
                $this->_default($command);
        }
        die('');
    }

    public function showFunctions() {
        foreach ($this->_functions as $f => $function) {
            if ($f[strlen($f) - 1] == ':') {
                $f = substr($f, 0, strlen($f) - 1);
            }
            if ($function[strlen($function) - 1] == ':') {
                $function = substr($function, 0, strlen($function) - 1);
            }
            echo "\tiris.php -h=$function (ou -h=$f)\n";
        }
    }

    abstract protected function _general();

    abstract protected function _url();

    abstract protected function _createProject();

    abstract protected function _removeProject();

    abstract protected function _alterProject();

    abstract protected function _default($command);

    abstract protected function _show();

    abstract public function error($number);

    //
    //  Database management
    //
    
    /**
     * 
     */
    protected function _dataBaseMenu() {
        echo <<<DATABASE
        iris.php -h=B  iris.php -h=database
        iris.php -h=b  iris.php -h=selectbase
        iris.php -h=I  iris.php -h=makedbini
        iris.php -h=e  iris.php --entitygenerate

DATABASE;
    }

    abstract protected function _database();

    abstract protected function _makeDbIni();

    abstract protected function _selectBase();

    abstract protected function _entityGenerate();

    abstract protected function _otherDB();

    public function GetInstance() {
        return new self(array());
    }

    /**
     * Return the name of the localized class for help
     *
     * @return string
     * @todo Verify if english version works for windows
     */
    public static function DetectLanguage() {
        $default = self::DEFAULTLANGUAGE;
// detection
        if (\Iris\OS\_OS::$OSName == 'LINUX') {
            $detected = explode(':', getenv('LANGUAGE'))[1];
        }
// windows
        else {
// What follows is awfull! But write me a nicer way
// to windows@thoorens.net
            $text = shell_exec('help dir');
            switch (explode(" ", $text)[0]) {
                case 'Affiche':
                    $detected = 'fr';
                    break;
                case 'Displays': // I am even sure
                    $detected = 'en';
                    break;
                default:
                    $detected = $default;
            }
        }
        switch ($detected) {
            case 'fr':
                $language = 'French';
                break;
            case 'en':
                $language = "English";
                break;
            default:
                echo "Sorry, your language is not available. Switching to default: $default.\n";
                $language = $default;
                break;
        }
        return $language;
    }

}
