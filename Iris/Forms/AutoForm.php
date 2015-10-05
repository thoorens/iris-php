<?php

namespace Iris\Forms;

/*
 * This file is part of IRIS-PHP distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2015 Jacques THOORENS
 */


/**
 * Description of AutoForm
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class AutoForm extends _AutoForm{

    

    public function __construct(\Iris\DB\_Entity $entity) {
        $this->_entity = $entity;
        $this->createFields();
    }

    public function createFields() {
        $entity = $this->_entity;
        $metadata = $entity->getMetadata();
        $config = new \Iris\SysConfig\Config('Test');
        foreach($metadata->getFields() as $field){
            $name = $field->getFieldName();
            $config = new \Iris\SysConfig\Config($name);
            $config->Type = strtolower($field->getType());
            $config->Label = $field->getFieldName().':';
            $this->_fields[$name] = $config;
            $this->_fieldOrder[] = $name;
        }
       // iris_debug($this->_fieldOrder);
//        $ini = new \Iris\SysConfig\IniParser();
//        $fileName = IRIS_PROGRAM_PATH.'/config/base/test.ini';
//        $ini->exportFile($fileName, $this->_fields, \Iris\SysConfig\IniParser::NO_INHERITANCE);
//        //iris_debug($this->_fields);
    }

}

