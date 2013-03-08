<?php

namespace Iris;

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

/* =========================================================================
 * C L A S S   L O G I T E M
 * =========================================================================/

  /**
 * This class breaks MVC model. It is used only in debugging context.
 * It manages one debugging message.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

class LogItem {

    /**
     *
     * @var type 
     */
    private $_message;

    /**
     *
     * @var string : title of the message (only used in AUTO mode)
     */
    private $_title;

    /**
     *
     * @var int : level of severity (has aspect incidence 
     */
    private $_severity;

    /**
     *
     * @var string (array) : enum for making style name 
     */
    private static $_Style = array(
        Log::SEV_INFO => 'loginfo',
        Log::SEV_DEBUG => 'logdebug',
        Log::SEV_PANIC => 'logpanic'
    );

    /**
     * Create a new logitem
     * @param string $message : content of the message
     * @param string $title : facultative title (only used in AUTO state)
     * @param int $severity : severity of the message 
     */
    public function __construct($message, $title, $severity) {
        $this->_severity = $severity;
        $this->_title = $title;
        $this->_message = $message;
    }

    /**
     * Prepare a message (with html tags and styles if necessary)
     * @param int $position : position of the log
     * @return string/NULL 
     */
    public function render($position = Log::POS_PAGE) {
        if ($position == Log::POS_FILE) {
            switch ($this->_severity) {
                case Log::SEV_INFO:
                    $text = "INFO    :";
                    break;
                case Log::SEV_DEBUG:
                    $text = "DEBUG   :";
                    break;
                case Log::SEV_WARNING:
                    $text = "WARNING :";
                    break;
                case Log::SEV_PANIC:
                    $text = "PANIC   :";
                    break;
                default:
                    $text = "????    :";
                    break;
            }
            $text .= " $this->_message\n";
            $fileName = IRIS_PROGRAM_PATH . '/log/message.log';
            file_put_contents($fileName, $text, FILE_APPEND);
        }
        else {
            $style = self::$_Style[$this->_severity];
            $text = "<div class=\"$style\">";
            if (!\is_null($this->_title)) {
                $text .= "<strong>$this->_title:</strong><hr>";
            }
            $text .= $this->_message . "</div>";
            return $text;
        }
    }

}

/* =========================================================================
 * C L A S S   L O G
 * =========================================================================/

/**
 * Manages messages during development. You can choose the position and 
 * severity of the message. The class doesn't use the trait tSingleton, 
 * because the static method recuperate has to modify the static var _Instance.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ 
 */

class Log {
    // Position

    const POS_NONE = 0; // no display
    const POS_PAGE = 1; // in the page (precise position depends on layout)
    const POS_AUTO = 2; // display at once (only for dirty debbugging purpose)
    const POS_FILE = 4; // in a log file
    // Severity : shown by color or icon
    const SEV_INFO = 1;
    const SEV_DEBUG = 2;
    const SEV_WARNING = 3;
    const SEV_PANIC = 4;

    protected static $Debug_Mode = 0;   // DEBUG_NONE

    /**
     *
     * @var \Iris\Log a singleton 
     */
    private static $_Instance = NULL;
    
    /**
     *
     * @var int position of the display 
     */
    private $_position = Log::POS_NONE;

    /**
     * 
     * @var LogItem (array) lines of log
     */
    private $_items = array();

    public function getPosition() {
        return $this->_position;
    }

    /**
     * Accessor set for position
     * @param int $position 
     */
    public function setPosition($position) {
        $this->_position = $position;
    }

    /**
     * Access to the singleton
     * 
     * @return Log 
     */
    public static function GetInstance() {
        if (\is_null(self::$_Instance)) {
            self::$_Instance = new Log();
        }
        return self::$_Instance;
    }
    

    /**
     * Save all content of log to Memory
     */
    public static function Save() {
        \Iris\Engine\Memory::Set('Log', self::GetInstance());
    }

    public static function Recuperate() {
        $memory = \Iris\Engine\Memory::GetInstance();
        if (isset($memory->Log)) {
            self::$_Instance = $memory->Log;
            $instance = self::GetInstance();
            if ($instance->getPosition() == self::POS_AUTO) {
                foreach ($instance->_items as $item) {
                    echo $item->render();
                }
            }
        }
    }

    /**
     * Log is a singleton
     */
    private function __construct() {
        
    }

    /**
     * Insert a new item in the log (display it if POS_AUTO
     *
     * @param LogItem $item 
     */
    private function insert($item) {
        if ($this->_position & (self::POS_AUTO | self::POS_FILE)) {
            echo $item->render();
        }
        // for later processingr
        $this->_items[] = $item;
    }

    /**
     * Insert a new message in the log (static function)
     * @global int \Iris\Log::$Debug_Mode : debug mode selectively set in index.php file
     * @param string $message : content of the message
     * @param int $level : select the class of debuging log of the message
     * @param string $title : facultative title (only used in AUTO state)
     * @param int $severity : severity of the message 
     */
    public static function Debug($message, $level = \Iris\Engine\Debug::NONE, $title = NULL, $severity = Log::SEV_DEBUG) {
        //echo "DEBUG :$message<br>"; 
        $show = \Iris\Log::$Debug_Mode & $level;
        if ($show) {
            $item = new LogItem($message, $title, $severity);
            self::GetInstance()->insert($item);
        }
    }

    /**
     * Prepare the logs for display and reset them
     * In production mode nothing happens
     * 
     * @return string 
     */
    public function render() {
        if ($this->_position != Log::POS_NONE) {
            if (Engine\Mode::IsDevelopment()) {
                $text = '';
                foreach ($this->_items as $item) {
                    $text .= $item->render($this->_position);
                }
                $this->_items = array();
                if ($this->_position == Log::POS_FILE) {
                    $fileName = IRIS_PROGRAM_PATH . '/log/message.log';
                    file_put_contents($fileName, $text, FILE_APPEND);
                }
                else {
                    return $text;
                }
            }
        }
    }

    /**
     * Add a category to be taken into account in the log system
     * 
     * @param int $flag 
     */
    public static function AddDebugFlag($flag) {
        self::$Debug_Mode += $flag;
    }

}

?>
