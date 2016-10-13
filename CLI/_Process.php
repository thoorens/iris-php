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
 * An abstract class containing some common methods for
 * Project, Code et CoreMaker
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $
 */
abstract class _Process {

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
     * @param Analyser $analyser The analyser used by of the command line
     */
    public function __construct($analyser) {
        $this->_analyser = $analyser;
        $this->_os = \Iris\OS\_OS::GetInstance();
    }

    /**
     * Restores an explicit option name (for short option)
     * and calls the corresponding method.
     */
    public function process() {
        $processingOption = $this->_analyser->getProcessingOption();
        list($method, $projectName) = explode('!', $processingOption . '!');
        if (strlen($method) == 1) {
            if (!isset(Analyser::$Functions[$method])) {
                $method .= ':';
            }
            $method = str_replace(':', '', Analyser::$Functions[$method]);
        }
        $methodName = "_$method";
        $this->$methodName($projectName);
    }

    /**
     * Creates a collection of directories in a named base
     * 
     * @param string[] $directories an array of directory names
     * @param string $base the directory to serve as a destination
     */
    protected function _createDir($directories, $base) {
        foreach ($directories as $directory) {
            if ($directory[0] !== '!') {
                $permissions = $this->_os->GetPrivateMod();
            }
            else{
                $directory = substr($directory, 1);
                $permissions = $this->_os->GetPublicMod();
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
     * @param mixed[] $replacement an associative array with the fields and values
     */
    protected function _createFile($source, $destination, $replacement = [], $backupNumber = 10) {
        $this->_checkExistingFile($destination, $backupNumber);
        $parameters = Parameters::GetInstance();
        $config = $parameters->getProject();
        $replacement['{PROJECTNAME}'] = "Project : $config->ProjectName";
        $replacement['{LICENSE}'] = $config->License;
        $replacement['{AUTHOR}'] = $config->Author;
        $replacement['{IRISVERSION}'] = \Iris\System\Functions::IrisVersion();
        $replacement['{COMMENT}'] = $config->Comment;
        $this->_os->createFromTemplate($source, $destination, $replacement);
    }

    /**
     *
     * @param string $fileName
     * @param int $backupNumber
     */
    protected function _checkExistingFile($fileName, $backupNumber = 10) {
        if (file_exists(($fileName))) {
            Analyser::Loader('/Iris/FileSystem/File.php');
            $file = new \Iris\FileSystem\File(basename($fileName), dirname($fileName));
            $file->backup($backupNumber);
            \Messages::Display('MSG_NEWFILEBU', $fileName);
        }
    }

    protected function _describeProcess($text) {
        echo $text;
    }

}
