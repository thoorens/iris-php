<?php

namespace Iris;

/*
 * This file is part of IRIS-PHP distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * A super factory to make auto forms
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class AutoFactory {

    /**
     * the factory uses the metadata entity to make the form
     */
    const AUTO = 1;

    /**
     * the factory uses a file to make the form
     */
    const FILE_BASED = 2;

    /**
     * the factory uses both the entity and a file to make the form
     */
    const MIXED = 3;

    /**
     *
     * @var int The way the factory creates the form
     */
    private $_mode = self::AUTO;

    /**
     *
     * @var boolean if true, the order of the fieds is explicitely specified 
     */
    private $_ordered = \FALSE;

    /**
     * the names of the different fields
     * 
     * @var string[]
     */
    private $_fieldNames = [];

    /**
     * the entity managed by the form
     * 
     * @var DB\Metadata
     */
    private $metadata;
    
    
    /**
     *
     * @var type 
     */
    private $_params;

    /**
     * 
     * @param DB\_Entity $entity
     * @return \Iris\AutoFactory
     */
    public static function EntityFactory($entity) {
        $autoForm = new AutoFactory($entity->getMetadata());
        $autoForm->_mode = self::AUTO;
        return $autoForm;
    }

    /**
     * 
     * @param type $fileName
     * @return \Iris\AutoFactory
     */
    public static function FileFactory($fileName) {
        $autoForm = new AutoFactory();
        $autoForm->_mode = self::FILE_BASED;
        $autoForm->readFile($fileName);
        return $autoForm;
    }

    /**
     * 
     * @param DB\_Entity $entity
     * @param string $fileName
     * @return \Iris\AutoFactory
     */
    public static function Factory($entity, $fileName) {
        $autoForm = new AutoFactory($entity->getMetadata());
        $autoForm->_mode = self::MIXED;
        $autoForm->readFile($fileName);
        return $autoForm;
    }

    /**
     * 
     * @param DB\Metadata $metadata
     */
    private function __construct($metadata = \NULL) {
        if (!is_null($metadata)) {
            $this->metadata = $metadata;
        }
    }

    /**
     * Specifies an order in the list of file
     * 
     * @return \Iris\AutoFactory
     */
    public function setOrdered() {
        $this->_ordered = \TRUE;
        return $this;
    }

    /**
     * 
     * @param type $fileName
     * @return \Iris\AutoFactory
     */
    public function readFile($fileName) {
        $parser = \Iris\SysConfig\_Parser::ParserBuilder('ini');
        $folder = SysConfig\Settings::$FormFolder;
        $filePath = \IRIS_PROGRAM_PATH .$folder.$fileName;
        $this->_params = $parser->processFile($filePath, \FALSE, \Iris\SysConfig\_Parser::NO_INHERITANCE);
        return $this;
    }

}
