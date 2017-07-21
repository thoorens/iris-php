<?php

namespace modules\db\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * Description of ChangeEM.php
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
trait tChangeEM {

    /**
     * Specifies the URL to return to
     * May be changed in _init() if the subclass needs it
     * 
     * @var string
     */
    protected $_returnURL = '';
    protected $_changeURL = '';

    /**
     * Puts the database name for sqlite
     * in the session variable
     */
    public function sqliteAction() {
        //$this->deletefileAction();
        $session = \Iris\Users\Session::GetInstance();
        $session->dbini = \Iris\DB\_EntityManager::SQLITE;
        $this->reroute($this->_returnURL);
    }

    /**
     * Puts the database name for postgresql
     * in the session variable
     */
    public function postgresqlAction() {
        //$this->deletedataAction();
        $this->redirect('impossible/postgresql');
        $session = \Iris\Users\Session::GetInstance();
        $session->dbini = \Iris\DB\_EntityManager::POSTGRESQL;
        $this->reroute($this->_returnURL);
    }

    /**
     * Puts the database name for mySQL (or MariaDB)
     * in the session variable
     */
    public function mysqlAction() {
        //$this->deletedataAction();
        $session = \Iris\Users\Session::GetInstance();
        $session->dbini = \Iris\DB\_EntityManager::MYSQL;
        $this->reroute($this->_returnURL);
    }

    /**
     * Puts the database name for Oracle
     * in the session variable
     */
    public function oracleAction() {
        //$this->deletedataAction();
        $this->redirect('impossible/oracle');
        $session = \Iris\Users\Session::GetInstance();
        $session->dbini = \Iris\DB\_EntityManager::ORACLE;
        $this->reroute($this->_returnURL);
    }

    public function impossibleAction($type){
        $this->__type = $type;
    }
//    public function changeAction(){
//        $buttons['mySQL'] = \Iris\DB\_EntityManager::MYSQL;
//        $buttons['SQLite'] = \Iris\DB\_EntityManager::SQLITE;
//        $buttons['PostgreSQL'] = \Iris\DB\_EntityManager::POSTGRESQL;
//        $buttons['Oracle'] = \Iris\DB\_EntityManager::ORACLE;
//        $this->__buttons = $buttons;
//        $this->__mc = 'forms/forms';
//    }
    
    /**
     * This action only display 4 links to permit the change tof
     * a new database management system
     */
    public function changeAction() {
        $buttons['mySQL'] = \Iris\DB\_EntityManager::MYSQL;
        $buttons['SQLite'] = \Iris\DB\_EntityManager::SQLITE;
        $buttons['PostgreSQL'] = \Iris\DB\_EntityManager::POSTGRESQL;
        $buttons['Oracle'] = \Iris\DB\_EntityManager::ORACLE;
        $this->__buttons = $buttons;
        $this->__controller = $this->_changeURL;
    }
}
