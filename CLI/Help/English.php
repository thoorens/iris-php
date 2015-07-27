<?php

namespace CLI\Help;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/*
 *  The english version of Help providing all help messages
 * While not tranlated, displays french version
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @todo Translate CLI help messages to english.
 *
 */

class English extends \CLI\_Help {

    /**
     *
     * @var \CLI\_Help
     */
    private $_french;

    function __construct($functions) {
        // while waiting for an english translation
        // we display the french version of help
        $this->_french = new French($functions);
        parent::__construct($functions);
    }

    protected function _alterProject() {
        $this->_sorryNoEnglish();
        $this->_french->_alterProject();
    }

    protected function _createProject() {
        $this->_sorryNoEnglish();
        $this->_french->_createProject();
    }

    protected function _default($command) {
        echo "Still no help defined for the option $command\n";
    }

    protected function _general() {
        $userConfigDir = IRIS_USER_PARAMFOLDER;
        $script = 'iris.php';
        echo <<<HELP
iris.php
========
This program offers an interface for various commands to create and manage
a site project realized with Iris-PHP

Functions:

    iris.php /path/to/IRIS/installation/directory

First invocation of the program and memorisation of the name of the folder
containing the framework libraries. This path is recorded in a parameter file
(~$userConfigDir$script under Linux)

    iris.php -h ou --help

HELP;
        echo "For a detailed help about a specific function, please type\n";
        $this->showFunctions();
    }

    protected function _removeProject() {
        $this->_sorryNoEnglish();
        $this->_french->_removeProject();
    }

    protected function _show() {
        $this->_sorryNoEnglish();
        $this->_french->_show();
    }

    protected function _url() {
        $this->_sorryNoEnglish();
        $this->_french->_url();
    }

    public function error($number) {
        $this->_sorryNoEnglish();
        $this->_french->error($number);
    }

    private function _sorryNoEnglish() {
        echo "At this time, no help is available in english for this function.\n";
        echo "Meanwhile, we display a help in french. Sorry for the inconvenience.\n";
    }

    protected function _makeDbIni() {
        $this->_sorryNoEnglish();
        $this->_french->_makeDbIni();
    }

    protected function _entityGenerate() {
        $this->_sorryNoEnglish();
        $this->_french->_entityGenerate();
    }

    protected function _otherDB() {
        $this->_sorryNoEnglish();
        $this->_french->_otherDB();
    }

    protected function _selectBase() {
        $this->_sorryNoEnglish();
        $this->_french->_selectBase();
    }

    protected function _database() {
        $this->_sorryNoEnglish();
        $this->_french->_database();
    }

}
