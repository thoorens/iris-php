<?php

namespace CLI\Help;

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
 *
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 *
 * @version $Id: $/**
 * The english version of Help providing all help messages
 * While not tranlated, displays french version
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

    }

}

