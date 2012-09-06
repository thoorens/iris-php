<?php

namespace models;

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
 * Small invoice manager for test purpose: abstract class
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _invoiceManager extends \Iris\DB\_Entity {

    protected static $_SQLCreate = array();
    private static $_Em = \NULL;
    private static $_File = '/library/IrisWB/application/config/base/invoice.sqlite';

    public static function getEM($dbType='sqlite') {
        if (is_null(self::$_Em)) {
            switch ($dbType) {
                case 'sqlite':
                    $file = self::$_File;
                    self::$_Em = \Iris\DB\_EntityManager::EMFactory("sqlite:$file");
            }
        }
        return self::$_Em;
    }

    public static function Reset($type) {
        switch ($type) {
            case 'sqlite':
                if (file_exists(IRIS_ROOT_PATH . self::$_File)) {
                    unlink(IRIS_ROOT_PATH . self::$_File);
                }
                break;
        }
    }

    public static function Create($type) {
        $em = self::getEM($type);
        $sql = static::$_SQLCreate[$type];
        $em->directSQL($sql);
    }

    public function __construct($EM = NULL) {
        if (is_null($EM)) {
            $EM = self::getEM();
        }
        parent::__construct($EM);
    }

}


