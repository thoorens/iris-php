<?php

namespace Iris\controllers\helpers;

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
 *
 * 
 */

/**
 * Manages the database state and warning messages
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class DbState extends \Iris\controllers\helpers\_ControllerHelper {

    /**
     * No know status
     */
    const UNKNOWN = 1;
    /**
     * A freshly created database
     */
    const CREATED = 2;
    /**
     * A database modified by the user
     */
    const MODIFIED = 3;
    /**
     * A deleted database
     */
    const DELETED = 4;

    protected $_singleton = TRUE;

    /**
     * Returns the only instance of the helper
     * 
     * @return \Iris\controllers\helpers\DbState
     */
    public function help() {
        return $this;
    }

    /**
     * Sets the dbState flag and stop the process if no database is present
     */
    public function validateDB() {
        if ($this->getCurrentState() == self::DELETED) {
            $this->redirect('error');
        }
    }

    /**
     * Returns an array with a message and a CSS class name describing the
     * database state
     * 
     * @return string[]
     */
    public function state() {
        return [$this->_warning(), $this->_cssClass()];
    }

    /**
     * Prepares a message to be displayed at the page bottom
     * 
     * @return string
     */
    private function _warning() {
        switch ($this->getCurrentState()) {
            case self::UNKNOWN:
                $message = "WARNING: the database of this example is perhaps not freshly created. Some request may crash the demo.";
                break;
            case self::CREATED:
                $message = "The database has been recently created. No modifications were done.";
                break;
            case self::MODIFIED:
                $message = "The database has been recently created but modifications have been done by the user.";
                break;
            case self::DELETED:
                $message = "The database has been deleted. No more management is possible. Please reset it with '/db/sample/init'";
                break;
        }
        return $message;
    }

    /**
     * Prepares a CSS class name to select the color and aspect of the message
     * 
     * @return string
     */
    private function _cssClass() {
        switch ($this->getCurrentState()) {
            case self::UNKNOWN:
                $class = 'warningUnknown';
                break;
            case self::CREATED:
                $class = "warningCreated";
                break;
            case self::MODIFIED:
                $class = "warningModified";
                break;
            case self::DELETED:
                $class = "warningDeleted";
                break;
        }
        return $class;
    }

    /**
     * Sets the status to CREATED
     */
    public function setCreated() {
        $this->setCurrentState(self::CREATED);
    }

    /**
     * Sets the status to MODIFIED
     */
    public function setModified() {
        $this->setCurrentState(self::MODIFIED);
    }

    /**
     * Sets the status to DELETED
     */
    public function setDeleted() {
        $this->setCurrentState(self::DELETED);
    }

    /**
     * Sets the status to the required value (through the session manager)
     * @param int $state
     */
    private function setCurrentState($state) {
        \Iris\Users\Session::GetInstance()->wbDbState = $state;
    }

    /**
     * Returns the present state taken from the session manager
     * 
     */
    private function getCurrentState() {
        return \Iris\Users\Session::GetInstance()->getValue('wbDbState', self::UNKNOWN);
    }

}
