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
 * @copyright 2011-2014 Jacques THOORENS
 */

/**
 * A small trait to permit the use of another EntityManager and database for
 * the users table. Note that __classInit will be fired on TUsers loading.
 * This trait will be unnecessary in normal circonstances. It has been created to
 * give an clean aspect to the entity example.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
trait tOtherEM {
    
    /**
     * 
     * @var \Iris\DB\_EntityManager
     */
    static private $_PrivateEntityManager;

    /**
     * This method will be called upon class loading and will find the correct
     * entity manager for this demo table.
     */
    public static function __ClassInit() {
        self::$_PrivateEntityManager = \Iris\DB\_EntityManager::EMFactory('sqlite:library/IrisWB/application/config/base/acl.sqlite');
        //die('OK');
    }

    /*
     * Structure and content of the users table :
     * 
     * DROP TABLE IF EXISTS "users";
       CREATE TABLE "users" (
     *          "id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , 
     *          "Name" VARCHAR, "Role" VARCHAR, 
     *          "Email" VARCHAR, 
     *          "Password" VARCHAR);

     * INSERT INTO "users" VALUES(1,'jimmy','tester','jimmy@irisphp.org','83f790ff615e7d343eb91f7e7f220256e0');
     * INSERT INTO "users" VALUES(2,'jacky','tester','jacky@irisphp.org','83f790ff615e7d343eb91f7e7f220256e0');
     */
    
    
    /**
     * By overloading this method, we will mask the fact that the Users table is not in the same
     * database.
     * 
     * @return \Iris\DB\_Entity
     */
    public static function GetEntity() {
        return parent::GetEntity(self::$_PrivateEntityManager);
    }


    
}

