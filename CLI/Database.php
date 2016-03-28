<?php

namespace CLI;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
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

    const IRIS_DB_FOLDER = '/config/base/';
    const IRIS_DB_DEMOFILE = 'demo.sqlite';
    const IRIS_DB_INIFILE = '10_database.ini';
    const IRIS_DB_PARAMFILE = 'db.ini';

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
     */
    protected function _selectbase() {
        $parameters = Parameters::GetInstance();
        $parameters->requireDefaultProject();
        $baseId = $parameters->getDatabase();
        $dbConfigs = $this->_readDBConfigs();
        if (!isset($dbConfigs[$baseId])) {
            Messages::Abort('ERR_DBiD', $baseId);
            
        }
        Messages::Display('', $parameters->getDatabase(\TRUE),$baseId);
        $config = $parameters->getDefaultProjectName();
        $config->Database = $baseId;
        $parameters->saveProject();
    }

    /**
     * Creates a 10_datase.ini file containing current database settings
     */
    protected function _makedbini() {
        $parameters = Parameters::GetInstance();
        $parameters->requireDefaultProject();
        $source = IRIS_LIBRARY_DIR . '/CLI/Files/database/' . IRIS_DB_INIFILE;
        $base = $this->_getDBConfig();
        //iris_debug($base->maindb);
        if ($base->maindb == 0) {
            $name = $parameters->getDatabase('ERR_DB_NOTINI', $name);
        }
        $destination = $parameters->getProjectDir() . '/' . $parameters->getApplicationName();
        $destination .= '/config/' . self::IRIS_DB_INIFILE;
        if (file_exists($destination)) {
            Messages::Abort('ERR_DBINI', $destination);
        }
        if ($base->adapter == 'sqlite') {
            $finalFileName = str_replace('%application%', $parameters->getApplicationName(), $base->dbname);
            $pairs = [
                '{HOSTNAME}' => 'some_data_base_name',
                '{USER}' => 'some_user_name',
                '{PASSWORD}' => 'some_password',
                '{COM}' => ';',
                '{NAME}' => $finalFileName,
            ];
        }
        else {
            $pairs = [
                '{HOSTNAME}' => $base->hostname,
                '{USER}' => $base->username,
                '{PASSWORD}' => $base->password,
                '{COM}' => '',
                '{NAME}' => $base->dbname,
            ];
        }
        $pairs['{ADAPTER}'] = $base->adapter;
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
        $source = IRIS_LIBRARY_DIR . '/CLI/Files/database/';
        $destination = $projectDir . '/' . $applicationDir;
        $controller = Analyser::PromptUser(
                        "Choose the controller which will manage the CRUD operations", \TRUE, $parameters->getEntityName());
        $module = Analyser::PromptUser(
                        "Choose the module into which $controller will be inserted", $parameters->getModuleName());
        if ($module != $parameters->getModuleName()) {
            Analyser::Loader('/CLI/Code');
            $code = new Code($this->_analyser);
            $code->makeNewCode($module, \NULL, \NULL);
        }
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
            '{PHP_TAG}' => '<?php',
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
     */
    protected function _database($function) {
        $parameters = Parameters::GetInstance();
        $parameters->requireDefaultProject();
        switch (strtolower($function)) {
            case "show":
                $this->_subShowDatabase();
                break;
            case "list":
                $this->_subListDatabase();
                break;
            case "create":
                $this->_subCreateDatabase();
                break;
            case 'ini':
                $this->_subShowIniFile();
                break;
            default:
                \Messages::Abort('ERR_BADDBFUNC',$function);
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
     */
    private function _subListDatabase() {
        $configs = $this->_readDBConfigs();
        echo "List of known databases:\n";
        echoLine(Parameters::LINE);
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
     */
    private function _subCreateDatabase() {
        $parameters = Parameters::GetInstance();
        $configs = $this->_readDBConfigs(\TRUE);
        $dbid = Analyser::PromptUser('Database id (unique internal value)', '');
        if ($dbid == '') {
            Messages::Abort('ERR_DBSHORT');
        }
        elseif (isset($configs[$dbid])) {
            Messages::Abort('ERR_EXISTID', $dbid);
        }
        $config = new \Iris\SysConfig\Config($dbid);
        $config->adapter = Analyser::PromptUser('Adapter name ', \TRUE, 'sqlite');
        if ($config->adapter == 'sqlite') {
            $applicationDir = $parameters->getApplicationName();
            $dbdir = Analyser::PromptUser('Directory ', \TRUE, "/$applicationDir" . self::IRIS_DB_FOLDER);
            $dbfile = Analyser::PromptUser('Database file ', \TRUE, self::IRIS_DB_DEMOFILE);
            $sep = '/';
            if (substr($dbdir, strlen($dbdir) - 1) == $sep or $dbfile[0] == $sep) {
                $sep = '';
            }
            $config->dbname = $dbdir . $sep . $dbfile;
            if (!file_exists($parameters->getProjectDir() . $config->dbname)) {
                echo "Warning {$parameters->getProjectDir()}$config->dbname does not exist.\n";
            }
        }
        else {
            $config->dbname = Analyser::PromptUser("Database name ");
            $config->hostname = Analyser::PromptUser("Host name ", \TRUE, 'localhost');
            $config->username = Analyser::PromptUser("User name ");
            $config->password = Analyser::PromptUser("Password (will be echoed) ");
        }
        $config->maindb = Analyser::PromptUserLogical("Database managed by config INI file ", "TRUE");
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
        echoLine(Parameters::LINE);
        echo "Project name        : $projectName\n";
        echo "Database adapter    : $base->adapter\n";
        if ($base->adapter == 'sqlite') {
            echo "File name           : $base->dbname\n";
        }
        else {
            echo "Host name          : $base->hostname\n";
            echo "Database           : $base->dbname\n";
            echo "User name          : $base->username\n";
            if ($base->password != '') {
                echo "Password           : == defined in file (not listed for security reasion) ==\n";
            }
            else {
                echo "Password           : undefined\n";
            }
        }
        $management = $base->maindb == 1 ? 'YES' : 'NO';
        echo "Managed by INI file : $management\n";
    }

    /**
     * Gives all the known setting of the database associated to the project
     * Correspond to --database ini
     */
    private function _subShowIniFile() {
        $fileName = $this->_getParamFileName();
        echo(file_get_contents($fileName));
        echo ("\n");
    }

    /*
     * End of subfunctions
     * ------------------------------------------------------------------------- */

    /**
     * Returns the database settings associated to the current project.
     *
     * @return \Iris\SysConfig\Config
     */
    private function _getDBConfig() {
        $parameters = Parameters::GetInstance();
        $parameters->requireDefaultProject();
        $baseName = $parameters->getDatabase();
        if ($baseName == "==NONE==") {
            \Messages::Abort('ERR_DBNONE', $baseName);
        }
        $fileName = $this->_getParamFileName();
        $configs = $parameters->readParams($fileName);
        if (!isset($configs[$baseName])) {
            \Messages::Abort('ERR_UNDEFDB', $baseName);
        }
        return $configs[$baseName];
    }

    /**
     * Returns the name of the file containing the database descriptions
     *
     * @return string
     */
    private function _getParamFileName() {
        die('IRIS_DB_PARAMFILE removed');
        $os = \Iris\OS\_OS::GetInstance();
        $paramDir = $os->getUserHomeDirectory() . FrontEnd::IRIS_USER_FOLDER;
        $paramFile = $paramDir . self::IRIS_DB_PARAMFILE;
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
        //iris_debug('Since modification in _Entity, this part is obsolete. Modify it');
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
            $ok = \FALSE;
            $settings = [
                self::ID => '',
                self::DESCRIPTION => '',
                self::TOOLTIP => '',
            ];
            while (!$ok) {
                //@todo suppress dependency from translation implementation
                $files = [
                    '/Iris/Translation/tSystemTranslatable.php',
                    '/Iris/Translation/iTranslatable.php',
                    '/Iris/views/helpers/tViewHelperCaller.php',
                    '/Iris/Design/iSingleton.php',
                    '/Iris/Subhelpers/_Subhelper.php',
                    '/Iris/Subhelpers/_LightSubhelper.php',
                    '/Iris/Subhelpers/Icon.php',
                    '/Iris/Subhelpers/_CrudIconManager.php',
                    '/CLI/Fake/CrudIconManager.php',
                    '/Iris/Translation/_Translator.php',
                    '/Iris/Translation/SystemTranslator.php',
                    '/CLI/Fake/CrudIconManager.php',
                ];
                Analyser::Loader($files);

                $settings[self::ID] = Analyser::PromptUser('Primary key name ', $settings[self::ID]);
                $settings[self::DESCRIPTION] = Analyser::PromptUser('Field containing a good description', $settings[self::DESCRIPTION]);
                $settings[self::TOOLTIP] = Analyser::PromptUser('Gender and description ', $settings[self::TOOLTIP]);

                /* @var $crud \Iris\Subhelpers\_CrudIconManager */
                $crud = Fake\CrudIconManager::GetInstance();
                $crud->setEntity($settings[self::TOOLTIP]);
                echo "Show tooltip:\n";
                echo "----------------";
                echo "In french:\n";
                $id = $settings[self::ID];
                $description = $settings[self::DESCRIPTION];
                $crud->setIdField($id);
                $crud->setDescField($description);
                $crud->setData([
                    $id => '1',
                    $description => "«Field_$description" . " of item $id »",
                ]);
                foreach (['french', "english"] as $language) {
                    echo "Show tooltip:\n";
                    echo "----------------";
                    echo "In $language:\n";
                    $crud->forceLanguage($language);
                    echo $crud->makeTooltip('create') . "\n";
                    echo $crud->makeTooltip('update') . "\n";
                }
                $ok = Analyser::PromptUserLogical('Is it ok? ', $ok);
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
     */
    private function _readDBConfigs($create = \FALSE) {
        $paramFile = FrontEnd::GetFilePath('db', 'test');
        if (file_exists($paramFile)) {
            $parser = \Iris\SysConfig\_Parser::ParserBuilder('ini');
            $configs = $parser->processFile($fileName);
        }
        elseif ($create) {
            $configs = [];
        }
        else {
            \Messages::Abort('ERR_NODBINI');
        }
        return $configs;
    }

}
