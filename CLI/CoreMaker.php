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
 * CoreMaker makes copies of classic class to permit the user
 * to customized them easily
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ * 
 */
class CoreMaker extends _Process {

    const CLASS_FILE_NAME = '/config/00_overriddenclasses.php';

    /**
     * Theses classes cannot be overidden
     * 
     * @var string[]
     * @todo see Log status
     */
    private $_protectedClasses = [
        'Iris\\Engine\\Bootstrap',
        'Iris\\Engine\\LoadLoader',
        'Iris\\Engine\\Loader',
        'Iris\\Engine\\Debug',
        'Iris\\Engine\\Mode',
        'Iris\\Engine\\PathArray',
        'Iris\\Log',
    ];

    /**
     * Permits to override any class in the framework (except those in 
     * the _protectedClasses array). A copy of the original class is made 
     * in library/core directory and a subclass ready for customisation is created
     * in library/extensions directory. The class is added to the list in config/overriddenclasses.php 
     */
    protected function _makecore() {
        // verify there is a default project
        $parameters = Parameters::GetInstance();
        $parameters->requireDefaultProject();
        // verify the class is not an invalid one
        $className = $parameters->getClasseName();
        if (array_search($className, $this->_protectedClasses) !== FALSE) {
            throw new \Iris\Exceptions\CLIException("Class $className can't be overridden through CLI. 
Overwrite __construct in public/Bootstrap.php instead and load your own class manually.");
        }
        // Please no initial backslash
        if($className == '\\'){
            $className = substr($className,1);
        }
        $project = $parameters->getCurrentProject();
        $libraryName = $this->_getLibraryName($project);
        $iris_library = $project->ProjectDir . "/$libraryName/";
        $classPath = str_replace('\\', '/', $className) . '.php';
        $fromPath = $iris_library . $classPath;
        if (!file_exists($fromPath)) {
            throw new \Iris\Exceptions\CLIException("Class $className does not exist. Don't forget to use double \\ in CLI.");
        }

        // copy class to core_class
        $corePath = $this->_newFileAndDir($iris_library, 'Core/', $classPath);
        $fileContent = file_get_contents($fromPath);
        $fileContent2 = preg_filter('/(final )?(class) ([a-z_A-Z])/', '$2 core_$3', $fileContent);
        $fileContent3 = str_replace('private', 'protected', $fileContent2);
        if (file_exists($corePath)) {
            echo "Warning : the file $corePath has been replaced by a fresh copy 
of the class from the current version of Iris-PHP.\n";
        }
        file_put_contents($corePath, $fileContent3);

        // make new class
        $extendPath = $this->_newFileAndDir($iris_library, 'Extensions/', $classPath);
        if (file_exists($extendPath)) {
            throw new \Iris\Exceptions\CLIException("The file $extendPath exists. It may be convenient to check its content.");
        }
        $arClass = explode('\\', $className);
        $class = array_pop($arClass);
        $namespace = implode('\\', $arClass);
        $file = <<<END
<?php

namespace $namespace;


   /**
    * Modified class "$className"
    * Add your own code...    
    */
    class $class extends core_$class {
    
    }
END;
        file_put_contents($extendPath, $file);

        // add the class to overridden.classes file
        $extendPath = $project->ProjectDir . '/' . $parameters->getApplicationDir() . self::CLASS_FILE_NAME;
        $text = "\t\\Iris\\Engine\\Loader::";
        $text .= '$UserClasses' . "['$className']=\\TRUE;\n";
        if (!file_exists($extendPath)) {
            file_put_contents($extendPath, "<?php\n");
        }
        file_put_contents($extendPath, $text, FILE_APPEND);
    }

    /**
     *
     * @param type $library
     * @param type $base
     * @param type $classPath
     * @return string 
     */
    private function _newFileAndDir($library, $base, $classPath) {
        $toPath = $library . $base . $classPath;
        $destinationDir = dirname($toPath);
        if (!file_exists($destinationDir)) {
            mkdir($destinationDir, 0777, TRUE);
        }
        return $toPath;
    }

    /**
     * Generates a new file 'overridden.classes' from the core directory 
     * content.
     */
    protected function _searchcore() {
        $parameters = Parameters::GetInstance();
        $parameters->requireDefaultProject();
        $project = $parameters->getCurrentProject();
        $iris_library = $this->_getLibraryName($project);
        $classes = [];
        $this->_readCoreFile($project->ProjectDir . '/' . $iris_library . '/Core', '', $classes);
        $text = "<?php\n";
        foreach ($classes as $class) {
            $class = substr($class,1);
            echo "Adding class $class\n";
            $text .= sprintf("\t\\Iris\\Engine\\Loader::\$UserClasses['%s']=\\TRUE;\n", $class);
        }
        if (count($classes)) {
            $toPath = $project->ProjectDir . '/' . $parameters->getApplicationDir() . self::CLASS_FILE_NAME;
            $this->_checkExistingFile($toPath);
            file_put_contents($toPath, $text);
            echo "File $toPath has been created.\n";
        }
        else {
            echo 'No extended class has been found.\n';
        }
    }

    /**
     *
     * @param string $basedir
     * @param type $classes 
     */
    private function _readCoreFile($basedir, $dirname, &$classes) {
        //echo "Rep: ".$basedir->getPathname()."\n";
        foreach (new \DirectoryIterator($basedir) as $file) {
            $fileName = $file->getFilename();
            //echo "Examine $file\n";
            if ($file->isFile()) {
                if ($file->getExtension() == 'php') {
                    $classes[] = $dirname . '\\' . basename($fileName, '.php');
                }
                //echo "Fichier : $fileName\n";
            }
            if ($file->isDir() and !$file->isDot()) {
                //echo "Enter in $fileName\n";
                $this->_readCoreFile($file->getPathname(), $dirname . '\\' . $file->getFileName(), $classes);
            }
        }
    }

    /**
     *
     * @param \Iris\SysConfig\Config $project
     * @return string
     */
    private function _getLibraryName($project) {
        // some project have custom library names
        $libraryName = $project->LibraryDir;
        if (is_null($libraryName)) {
            $libraryName = 'library'; // default
        }
        return $libraryName;
    }

}


