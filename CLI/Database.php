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
 * @copyright 2011-2013 Jacques THOORENS
 *
 */



/**
 * This class manage the code creation for the database management:<ul>
 * <li>database definition and selection
 * <li>creation of ini file
 * <li>creation of CRUD management with controller/model/crud file et scripts
 * </ul>
 * 
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ * 
 */

class Database extends _Process {

    /**
     * Symbolic names for the database settings
     */
    const DEF = 0;
    const CALL = 1;
    const EXTERNAL = 2;
    
    /**
     * Symbolic names for the crudicon settings
     */
    const ID = 0;
    const DESCRIPTION = 1;
    const TOOLTIP = 2;

    /**
     * Selects a database for the default project
     * 
     * @throws \Iris\Exceptions\CLIException
     */
    protected function _selectbase() {
        $parameters = Parameters::GetInstance();
        $parameters->requireDefaultProject();
        $baseId = $parameters->getDatabase();
        $dbConfigs = $this->_readDBConfigs();
        if (!isset($dbConfigs[$baseId])) {
            throw new \Iris\Exceptions\CLIException("
No database with id $baseId has been found in the system. 
Choose another name ('iris.php -B list' to see the existing names)
or create it before whith 'iris.php -B create $baseId'.");
        }
        echo "Switching database from " . $parameters->getDatabase(\TRUE) . " to $baseId\n";
        $config = $parameters->getCurrentProject();
        $config->Database = $baseId;
        $parameters->saveProject();
    }

    /**
     * Creates a 10_datase.ini file containing current database settings
     * 
     * @throws \Iris\Exceptions\CLIException
     */
    protected function _makedbini() {
        $parameters = Parameters::GetInstance();
        $parameters->requireDefaultProject();
        $source = Analyser::GetIrisLibraryDir() . '/CLI/Files/database/10_database.ini';
        $base = $this->_getDBConfig();
        if ($base->maindb == 0) {
            $name = $parameters->getDatabase();
            throw new \Iris\Exceptions\CLIException("The database $name is not managed by an INI file.\n");
        }
        $destination = $parameters->getProjectDir() . '/' . $parameters->getApplicationName();
        $destination .= '/config/10_database.ini';
        if (file_exists($destination)) {
            throw new \Iris\Exceptions\CLIException("A file $destination already exists.
Would you please edit it by hand according to your database settings?
You can also delete it and rerun 'iris.php --makedbini'.\n");
        }
        if ($base->adapter == 'sqlite') {
            $pairs = [
                '{HOSTNAME}' => 'some_data_base_name',
                '{USER}' => 'some_user_name',
                '{PASSWORD}' => 'some_password',
                '{COM}' => ';',
            ];
        }
        else {
            $pairs = [
                '{HOSTNAME}' => $base->hostname,
                '{USER}' => $base->username,
                '{PASSWORD}' => $base->password,
                '{COM}' => '',
            ];
        }
        $pairs['{ADAPTER}'] = $base->adapter;
        $pairs['{NAME}'] = $base->dbname;
        \Iris\OS\_OS::GetInstance()->createFromTemplate($source, $destination, $pairs);
        echo "File $destination now contains all your settings.\n";
    }

    
    /**
     * Creates all the necessary files for managing a table in the default
     * database. Existing files are backed up. 5 files are created:<ul>
     * <li>a model/entity file
     * <li>a CRUD file
     * <li>a controller (whose name is in -C parameter)
     * <li>a view to choose the line to edit
     * <li>a view to display and edit a line
     * </ul>
     */
    protected function _entitygenerate() {
        $parameters = Parameters::GetInstance();
        $parameters->requireDefaultProject();
        $projectDir = $parameters->getProjectDir();
        $applicationDir = $parameters->getApplicationName();
        $source = Analyser::GetIrisLibraryDir() . '/CLI/Files/database/';
        $destination = $projectDir . '/' . $applicationDir;
        $module = $parameters->getModuleName();
        $controller = $parameters->getControllerName();
        $entityName = ucfirst($parameters->getEntityName());
        // files to copy
        $files = [
            "TEntity.php" => "/models/T$entityName.php",
            "Entity.php" => "/models/crud/$entityName.php",
            "controller.php" => "/modules/$module/controllers/$controller.php",
            "controller_index.iview" => "/modules/$module/views/scripts/$controller" . "_index.iview",
            "controller_editall.iview" => "/modules/$module/views/scripts/$controller" . "_editall.iview",
        ];
        // 
        $pairs = [
            '{ENTITY}' => $entityName,
            '{entity}' => strtolower($entityName),
            '{MODULE}' => $module,
            '{CONTROLLER}' => $controller,
            '{GETEMDEF}' => $this->_getEntityManagerSettings($entityName, self::DEF),
            '{GETEMCALL}' => $this->_getEntityManagerSettings($entityName, self::CALL),
            '{EXTERNALEM}' => $this->_getEntityManagerSettings($entityName, self::EXTERNAL),
            '{ID}' => $this->_getIconSettings(self::ID),
            '{=DESCRIPTION=}' => $this->_getIconSettings(self::DESCRIPTION),
            '{TOOLTIP}' => $this->_getIconSettings(self::TOOLTIP),
        ];
        foreach ($files as $fromFile => $toFile) {
            echo "Generates $toFile from $fromFile\n";
            $this->_createFile($source . $fromFile, $destination . $toFile, $pairs);
        }
    }

    /**
     * The entry point for database management subfunctions 
     * 
     * @param string $function one among show, list, create
     * @throws \Iris\Exceptions\CLIException
     */
    protected function _database($function) {
        $parameters = Parameters::GetInstance();
        $parameters->requireDefaultProject();
        switch ($function) {
            case "show":
                $this->_subShowDatabase();
                break;
            case "list":
                $this->_subListDatabase();
                break;
            case "create":
                $this->_subCreateDatabase();
                break;
            default:
                throw new \Iris\Exceptions\CLIException("Function --database $function not implemented.");
        }
    }

    /* ======================================================================================================
     * Subfonctions for --database :
     * list
     * create
     * show
     * ====================================================================================================== */

    /**
     * Lists all the known databases used by all the project
     * Correspond to --database list
     * 
     * @throws \Iris\Exceptions\CLIException
     */
    private function _subListDatabase() {
        $paramFile = $this->_getParamFileName();
        if (!file_exists($paramFile)) {
            throw new \Iris\Exceptions\CLIException("No database has been defined by the current user.");
        }
        $configs = $this->_readDBConfigs();
        echo "List of known databases:\n";
        echo "------------------------\n";
        foreach ($configs as $id => $config) {
            if ($config->adapter == 'sqlite') {
                echo sprintf("%-10s: %s (sqlite)\n", $id, $config->dbname);
            }
            else {
                echo sprintf("%-10s: %s@%s:%s (%s)\n", $id, $config->username, $config->hostname, $config->dbname, $config->adapter);
            }
        }
    }

    /**
     * Creates a database definition in db.ini for later use. This definition will be associated with 
     * a project.
     * Correspond to --database create
     * 
     * @throws \Iris\Exceptions\CLIException
     */
    private function _subCreateDatabase() {
        $parameters = Parameters::GetInstance();
        $analyser = $this->_analyser;
        $configs = $this->_readDBConfigs(\TRUE);
        $dbid = $analyser->promptUser('Database id (unique internal value)', '');
        if ($dbid == '') {
            throw new \Iris\Exceptions\CLIException('The database id must be at least one letter long.');
        }
        elseif (isset($configs[$dbid])) {
            throw new \Iris\Exceptions\CLIException("A database with the id $dbid is already referenced. You can use it.");
        }
        $config = new \Iris\SysConfig\Config($dbid);
        $config->adapter = $analyser->promptUser('Adapter name ', 'sqlite');
        if ($config->adapter == 'sqlite') {
            $applicationDir = $parameters->getApplicationName();
            $dbdir = $analyser->promptUser('Directory ', "/$applicationDir/config/base/");
            $dbfile = $analyser->promptUser('Database file ', 'demo.sqlite');
            $config->dbname = $dbdir . $dbfile;
            if (!file_exists($parameters->getProjectDir() . $config->dbname)) {
                echo "Warning {$parameters->getProjectDir()}$config->dbname does not exist.\n";
            }
        }
        else {
            $config->dbname = $analyser->promptUser("Database name ", '');
            $config->hostname = $analyser->promptUser("Host name ", 'localhost');
            $config->username = $analyser->promptUser("User name ");
            $config->password = $analyser->promptUser("Password (will be echoed) ");
        }
        $config->maindb = $analyser->promptUserLogical("Database managed by config INI file ", "TRUE");
        $configs[$dbid] = $config;
        $parameters->writeParams($this->_getParamFileName(), $configs);
    }

    /**
     * Gives all the known setting of the database associated to the project
     * Correspond to --database show 
     */
    private function _subShowDatabase() {
        $base = $this->_getDBConfig();
        $projectName = Parameters::GetInstance()->getProjectName();
        echo "Default database definition:\n";
        echo "----------------------------\n";
        echo "Project name        : $projectName\n";
        echo "Database adapter    : $base->adapter\n";
        if ($base->adapter == 'sqlite') {
            echo "File name           : $base->dbname\n";
        }
        else {
            echo "Host name          : $base->hostname\n";
            echo "Database           : $base->dbname\n";
            echo "User name          : $base->username\n";
            if ($this->password != '') {
                echo "Password           : == defined in file ==\n";
            }
            else {
                echo "Password           : undefined\n";
            }
        }
        $management = $base->maindb == 1 ? 'YES' : 'NO';
        echo "Managed by INI file : $management\n";
    }

    /*
     * End of subfunctions 
     * ------------------------------------------------------------------------- */

    /**
     * Returns the database settings associated to the current project.
     * 
     * @return \Iris\SysConfig\Config
     * @throws \Iris\Exceptions\CLIException
     */
    private function _getDBConfig() {
        $parameters = Parameters::GetInstance();
        $parameters->requireDefaultProject();
        $baseName = $parameters->getDatabase();
        if ($baseName == "==NONE==") {
            throw new \Iris\Exceptions\CLIException("No database associated to the project");
        }
        $fileName = $this->_getParamFileName();
        $configs = $parameters->readParams($fileName);
        if (!isset($configs[$baseName])) {
            throw new \Iris\Exceptions\CLIException("The database $baseName is not defined");
        }
        return $configs[$baseName];
    }

    /**
     * Returns the name of the file containing the database descriptions
     * 
     * @return string
     */
    private function _getParamFileName() {
        $os = \Iris\OS\_OS::GetInstance();
        $paramDir = $os->getUserHomeDirectory() . "/.iris";
        $paramFile = $paramDir . "/db.ini";
        return $paramFile;
    }

    /**
     * Returns a setting of the database by its number (symbolic const) to 
     * serve as a replacement value in the template files.
     * 
     * @param string $entityName
     * @param int $settingId the field to fetch
     * @return type
     */
    private function _getEntityManagerSettings($entityName, $settingId) {
        static $settings = \NULL;
        if (is_null($settings)) {
            $baseConfig = $this->_getDBConfig();
            $settings = ['', '', ''];
            if ($baseConfig->maindb != 1) {
                $adapter = $baseConfig->adapter;
                $dbname = $baseConfig->dbname;
                $settings[self::CALL] = ', self::GetEM()';
                if ($adapter == 'sqlite') {
                    $params = "'sqlite:$dbname'";
                }
                else {
                    $host = $baseConfig->hostname;
                    $dsn = "$adapter:host=$host;dbname=$dbname";
                    $username = $baseConfig->username;
                    $password = $baseConfig->password;
                    $params = "'$dsn', '$username', '$password'";
                }
                $settings[self::DEF] = <<<END
public static function GetEM(){
        return \Iris\DB\_EntityManager::EMFactory($params);
    }
END;
                $settings[self::EXTERNAL] = "protected static \$_EMProviderClass = '\\models\\crud\\$entityName';";
            }
        }
        iris_debug('Since modification in _Entity, this part is obsolete. Modify it');
        return $settings[$settingId];
    }

    /**
     * Returns a setting of the crudicon by its number (symbolic const) to 
     * serve as a replacement value in the template files.
     * 
     * @param int $settingId the field to fetch
     * @return type
     */
    private function _getIconSettings($settingId) {
        static $settings = \NULL;
        if (is_null($settings)) {
            $analyser = $this->_analyser;
            $ok = \FALSE;
            $settings = [
                self::ID => '',
                self::DESCRIPTION => '',
                self::TOOLTIP => '',
            ];
            while (!$ok) {
                //@todo suppress dependency from translation implementation
                $classes = [
                    '/Iris/Translation/tSystemTranslatable',
                    '/Iris/Design/iSingleton',
                    '/Iris/Subhelpers/_Subhelper',
                    '/Iris/Subhelpers/Crud',
                    '/Iris/Translation/_Translator',
                    '/Iris/Translation/SystemTranslator',
                ];
                $front = new \FrontEnd();
                $front->preloadClasses($classes);

                $settings[self::ID] = $analyser->promptUser('Primary key name ', $settings[self::ID]);
                $settings[self::DESCRIPTION] = $analyser->promptUser('Field containing a good description', $settings[self::DESCRIPTION]);
                $settings[self::TOOLTIP] = $analyser->promptUser('Gender and description ', $settings[self::TOOLTIP]);

                $crud = \Iris\Subhelpers\Crud::GetInstance();
                $crud->setEntity($settings[self::TOOLTIP]);
                echo "Show tooltip:\n";
                echo "----------------";
                echo "In french:\n";
                \Iris\Translation\_Translator::SetLanguage('French');
                echo $crud->testCLI('create') . "\n";
                echo $crud->testCLI('delete') . "\n";
                //@todo show examples in english
//                echo "In english:\n";
//                \Iris\Translation\_Translator::SetLanguage('English');
//                echo $crud->testCLI('create') . "\n";
//                echo $crud->testCLI('delete') . "\n";
                $ok = $analyser->promptUserLogical('Is it ok? ', $ok);
            }
        }
        return $settings[$settingId];
    }

    /**
     * Read the settings for all the known database. If no db.ini file found,
     * creates one or throws an exception according to the optional parameter 
     * (by default, exception)
     * 
     * @param boolean $create
     * @return array(Config)
     * @throws \Iris\Exceptions\CLIException
     */
    private function _readDBConfigs($create = \FALSE) {
        $parameters = Parameters::GetInstance();
        $paramFile = $this->_getParamFileName();
        if (file_exists($paramFile)) {
            $configs = $parameters->readParams($paramFile);
        }
        elseif ($create) {
            $configs = array();
        }
        else {
            throw new \Iris\Exceptions\CLIException("No database has been defined by the current user.");
        }
        return $configs;
    }

}
