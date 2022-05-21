<?php
namespace Iris\TextFormat;

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
 * Description of newIrisClass
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Object {
    
    const folderName = 'folder';
    
    protected $_innerObject;
    
    public function __construct($object) {
        $this->_innerObject = $object;
    }
    
    /**
     * 
     * @param type $name
     * @return string
     */
    public function _get($name){
        return $this->_innerObject->$name;
    }
    
    /**
     * 
     * @param \Iris\DB\Record $object
     */
    public function copyData($object){
        foreach($object->asArray() as $key => $value){
            $this->_fields[$key] = $value;
        }
    }
    
    /**
     * 
     * @param type $name
     * @param type $value
     * @return \TextFormat\Object
     */
    public function _set($name, $value){
        $this->_innerObject->$name = $value;
        return $this;
    }

    public function getFolder() {
        $folderName = static::folderName;
        return $this->_innerObject->$folderName;
    }

}


