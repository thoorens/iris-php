<?php

namespace Iris\Errors;

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
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * Thank you to Eric Daspet and Cyril Pierre de Geyser
 * for their clear explanations in their book
 * "PHP avancé" (Editions Eyrolles)
 */

/**
 * This class groups all the error parameters
 * 
 */
class Settings extends \Iris\SysConfig\_Settings {

    const TYPE_STANDARD = 1;
    const TYPE_PRIVILEGE = 2;
    const TYPE_FATAL = 3;

    protected static $_GroupName = 'error';

    /**
     * Determines if the handler keep the emited text
     */

    const KEEP = 1;

    /**
     * Determines if the handler uses a log file
     */
    const LOG = 2;

    /**
     * Determines if the handler sends emails
     */
    const MAIL = 4;

    /**
     * Determines if the handler hangs on error (only for test)
     */
    const HANG = 8;

    /**
     * Determines if a final fatal error must be display
     */
    const FATAL = 16;

    /**
     * Determines if it is convenient to display flags in error toolbar
     */
    const FLAGS = 32;

    /**
     * Default values
     */
    protected function _init() {

        //Test if the echoed text must be kept and not erased before 
        // displaying the error messages
        \Iris\SysConfig\BooleanSetting::CreateSetting('keep', \FALSE);
        // Disables the use of a log file
        \Iris\SysConfig\BooleanSetting::CreateSetting('log', \FALSE);
        \Iris\SysConfig\StandardSetting::CreateSetting('logFile', '/log/iris.log');
        // Forbits the handler from sending an email to the administrator
        \Iris\SysConfig\BooleanSetting::CreateSetting('mail', \FALSE);
        // Disables the error hang mechanism
        \Iris\SysConfig\BooleanSetting::CreateSetting('hang', \FALSE);
        // In case of fatal error, a message has to be fired in development
        \Iris\SysConfig\BooleanSetting::CreateSetting('fatal', \FALSE);
        // Default error controller
        \Iris\SysConfig\StandardSetting::CreateSetting('controller', '/Error');
        // Title for error screen
        \Iris\SysConfig\StandardSetting::CreateSetting('title', 'Iris - Error');
        // The stack level to display
        \Iris\SysConfig\StandardSetting::CreateSetting('stackLevel', 0);
        $this->_forceSettings();
    }

    /**
     * Gets from URL optional ERROR parameters and
     * modifies the settings accordingly
     */
    private function _forceSettings() {
        if (isset($_GET['ERROR'])) {
            $data = \Iris\SysConfig\_Setting::GetGroup(self::$_GroupName);
            $errorValue = $_GET['ERROR'];
            if (is_numeric($errorValue)) {
                if ($errorValue & self::KEEP)
                    $data['keep']->enable();
                if ($errorValue & self::LOG)
                    $data['log']->enable();
                if ($errorValue & self::MAIL)
                    $data['mail']->enable();
                if ($errorValue & self::HANG)
                    $data['hang']->enable();
            }
            else {
                if ($errorValue == 'NOTFATAL') {
                    $data['fatal']->disable();
                }
                else {
                    $data[strtolower($errorValue)]->enable();
                }
            }
        }
        if (isset($_GET['ERRORSTACK'])) {
            $this->_data['stacklevel']->set($_GET['ERRORSTACK']);
        }
    }

    /* ---------------------------------------------------------------------------- */

    /**
     * Returns the internal flags (for debugging purpose)
     * 
     * @return int
     */
    public function getErrorflags() {
        $flags = 0;
        if (self::HasKeep())
            $flags += self::KEEP;
        if (self::HasLog())
            $flags += self::LOG;
        if (self::HasMail())
            $flags += self::MAIL;
        if (self::HasHang())
            $flags += self::HANG;
        if (self::HasFatal())
            $flags += self::FATAL;
        return $flags;
    }

}

// Auto init
Settings::__ClassInit();