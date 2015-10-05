<?php

namespace Iris\System;

defined('CRLF') or define('CRLF', "\n");

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Implements an internal repository for a class having named instances
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
trait tRepository {

    private static $_NextObjectNumber = 0;

    /**
     * All the object are placed in a repository
     *
     * @var static[]
     */
    private static $_Repository = array();

    /**
     * The name of the bubble
     *
     * @var string
     */
    private $_objectName;

    /**
     * A private constructor, each object is created or retrieved by its name.
     *
     * @param string $objectName The name of the new object
     */
    private function __construct($objectName) {
        $this->_objectName = $objectName;
    }

    /**
     * Returns an object (after creating it if necessary)
     * by its name
     *
     * @param string $objectName The name of the object to create/retrieve
     * @return static
     */
    protected static function _GetObject($objectName) {
        if (!isset(self::$_Repository[$objectName])) {
            self::$_Repository[$objectName] = self::_New($objectName);
        }
        return self::$_Repository[$objectName];
    }

    public static function NewObjectName($prefix = '') {
        if ($prefix == '') {
            $prefix = 'Object_';
        }
        return $prefix . ++self::$_NextObjectNumber;
    }

    /**
     * To override for most sophisticated constructor management (e.g. subhelper _SlideShowManager)
     *
     * @param type $objectName The name of the object to create
     * @return \static
     */
    protected static function _New($objectName) {
        return new static($objectName);
    }

    /**
     * Returns all the bubbles (used internally to generate the javascript
     * code.
     *
     * @return array
     */
    public static function GetAllObjects() {
        return self::$_Repository;
    }

    /**
     *
     * @param string $objectName The name of the object to delete
     */
    public static function DropObject($objectName) {
        if (isset(self::$_Repository[$objectName])) {
            unset(self::$_Repository[$objectName]);
        }
    }

    public static function InstanceNumber() {
        return count(self::$_Repository);
    }

    public function getName() {
        return $this->_objectName;
    }

}

