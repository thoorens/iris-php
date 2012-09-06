<?php

namespace CLI;
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
 * An abstract class containing some common methods for
 * Project, Code et CoreMaker
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $abstract class _Process {

    /**
     *
     * @var String méthode effectivement exécutée par process() 
     */
    protected $_method;

    /**
     *
     * @var Analyser 
     */
    protected $_analyser;

    /**
     *
     * @var \Iris\OS\_OS
     */
    protected $_os;

    /**
     * le constructeur initialise la variable _method en fonction
     * de l'option de la ligne de commande 
     * 
     * @param Analyser $analyser The analyser of the command line 
     */
    public function __construct($analyser) {
        $this->_analyser = $analyser;
    }

    /**
     *
     */
    public function process() {
        $option = $this->_analyser->getOption();
        list($method, $param) = explode('_', $option . '_');
        if (strlen($method) == 1) {
            if (!isset(Analyser::$Functions[$method])) {
                $method .= ':';
            }
            $method = str_replace(':', '', Analyser::$Functions[$method]);
        }
        $method = "_$method";
        $this->$method($param);
    }

    /**
     * Rewrite the configs to the project.ini file in ~/.iris directory
     * @param array $configs 
     */
    protected function _updateConfig($configs) {
        $os = \Iris\OS\_OS::GetOSInstance();
        $paramDir = $os->getUserHomeDirectory() . "/.iris";
        $this->_analyser->writeParams($paramDir . "/projects.ini", $configs);
    }

    protected function _createDir($directories, $base) {
        foreach ($directories as $directory) {
            $permissions = 0755;
            if($directory[0]=='!'){
                $directory = substr($directory,1);
                $permissions = 0777;
            }
            $directory = "$base/$directory";
            $this->_os->mkDir($directory, $permissions, TRUE);
        }
    }

    /**
     * Creates a file from a template. The fields to replace are provided
     * (the method adds some project related field value pairs.
     * 
     * @param string $source the path to the original template file
     * @param string $destination the path to the new file
     * @param array $replacement an associative array with the fields and values 
     */
    protected function _createFile($source, $destination, $replacement=array()) {
        $replacement['{PROJECTNAME}'] = $this->_analyser->getDetailedProjectName();
        $replacement['{LICENSE}'] = $this->_analyser->getLicense();
        $replacement['{AUTHOR}'] = $this->_analyser->getAuthor();
        $replacement['{IRISVERSION}'] = IRISVERSION;
        $replacement['{COMMENT}'] = $this->_analyser->getComment();
        $this->_os->createFromTemplate($source, $destination, $replacement);
    }

}

?>
