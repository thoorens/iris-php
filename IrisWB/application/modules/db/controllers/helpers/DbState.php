<?php

namespace Iris\controllers\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
     * Certainly modified, but in unknown initial state
     */
    const MODIFIED2 = 4;

    /**
     * A deleted database
     */
    const DELETED = 5;

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
    public function state($state = \NULL) {
        return [$this->_warning($state), $this->_cssClass($state)];
    }

    /**
     * Prepares a message to be displayed at the page bottom
     * 
     * @param mixed $state If not null, this state will be used instead of the current state
     * @return string
     */
    private function _warning($state = \NULL) {
        if(is_null($state)){
            $state = $this->getCurrentState();
        }
        switch ($state) {
            case self::UNKNOWN:
                $message = "WARNING: the database of this example is perhaps not freshly created. Some request may crash the demo.";
                break;
            case self::CREATED:
                $message = "The database has been recently created. No modifications were done.";
                break;
            case self::MODIFIED:
                $message = "The database has been recently created but modifications have been done by the user.";
                break;
            case self::MODIFIED2:
                $message = "The database is perhaps not freshly created, but modifications have been done by the user.";
                break;
            case self::DELETED:
                $message = "The database has been deleted. No more management is possible. Please reset it with '/db/sample/init' or use the button <b>Create the database</b>.";
                break;
            default:
                $message = "Warning! If you choose to make a change to another database system, the current database will be erased.";
                break;
        }
        return $message;
    }

    /**
     * Prepares a CSS class name to select the color and aspect of the message
     * 
     * @return string
     */
    private function _cssClass($state = \NULL) {
        if(is_null($state)){
            $state = $this->getCurrentState();
        }
        switch ($state) {
            case self::UNKNOWN:
                $class = 'warningUnknown';
                break;
            case self::CREATED:
                $class = "warningCreated";
                break;
            case self::MODIFIED:
                $class = "warningModified";
                break;
            case self::MODIFIED2:
                $class = "warningModified2";
                break;
            case self::DELETED:
                $class = "warningDeleted";
                break;
            default:
                $class = "warningDeletion";
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
        $previousState = $this->getCurrentState();
        if ($previousState == self::CREATED or $previousState == self::MODIFIED) {
            $this->setCurrentState(self::MODIFIED);
        }
        else {
            $this->setCurrentState(self::MODIFIED2);
        }
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
