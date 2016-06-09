<?php
namespace Iris\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * Manages messages during development. You can choose the position and 
 * severity of the message. The class doesn't use the trait tSingleton, 
 * because the static method Recover has to modify the static var _Instance.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ 
 */
class Log implements \Iris\Design\iSingleton{
    use tSingleton;
    
    
    // Positions of the Log messages
    
    /**
     * No log/debug messages will be written
     */
    const POS_NONE = 0;
    
    /**
     * The log/debug messages will be put in the page (precise position depends on layout)
     */
    const POS_PAGE = 1; // in the page (precise position depends on layout)
    
    /**
     * The log/debug messages will be displayed at once (only for dirty debbugging purpose)
     */
    const POS_AUTO = 2;
    
    /**
     * The log/debug messages will be written in a log file
     */
    const POS_FILE = 4;
    
    // Severity : shown by color or icon
    
    /**
     * A simple info
     */
    const SEV_INFO = 1;
    
    /**
     * A debug message
     */
    const SEV_DEBUG = 2;
    
    /**
     * A warning message
     */
    const SEV_WARNING = 3;
    
    /**
     * A fatatal warning message
     */
    const SEV_PANIC = 4;

    /**
     *
     * @var \Iris\Engine\Log a singleton 
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
    private $_items = [];

    public function getPosition() {
        return $this->_position;
    }

    /**
     * Accessor set for position. If necessary adds the iris_debug.css file
     * 
     * @param int $position 
     */
    public function setPosition($position) {
        $this->_position = $position;
        if($position != Log::POS_NONE){
            \Iris\views\helpers\StyleLoader::GetInstance()->load('/!documents/file/css/iris_debug.css');
        }
    }

    /**
     * Save all content of log to Memory
     */
    public static function Save() {
        \Iris\Engine\Memory::Set('Log', self::GetInstance());
    }

    public static function Recover() {
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
     * Insert a new item in the log (display it if POS_AUTO
     *
     * @param LogItem $item 
     */
    private function insert($item) {
        
        if ($this->_position & (self::POS_AUTO)) {
            echo $item->render();
        }
        // for later processing
        $this->_items[] = $item;
    }

    /**
     * Insert a new message in the log (static function)
     * 
     * @param string $message : content of the message
     * @param int $level : select the class of debuging log of the message
     * @param string $title : facultative title (only used in AUTO state)
     * @param int $severity : severity of the message 
     */
    public static function Debug($message, $level = \Iris\Engine\Debug::NONE, $title = NULL, $severity = Log::SEV_DEBUG) {
        $debugMode = \Iris\SysConfig\Settings::$DebugMode;
        $show = $debugMode & $level;
        //echo "DebugMode ".\Iris\SysConfig\Settings::$DebugMode. "- Level : $level - Show : $show <br/>";
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
            if (\Iris\Engine\Mode::IsDevelopment()) {
                $text = '';
                foreach ($this->_items as $item) {
                    $text .= $item->render($this->_position);
                }
                $this->_items = [];
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
        $old = \Iris\SysConfig\Settings::$DebugMode;
        \Iris\SysConfig\Settings::$DebugMode |= $flag;
        //echo "$old to ". \Iris\SysConfig\Settings::$DebugMode . '<br/>';
    }

    /**
     * 
     * @return string
     */
    public static function Page() {
        $instance = self::GetInstance();
        $debugInfo = '';
        if($instance->_position == self::POS_PAGE){
            $debugInfo = $instance->render();
        }
        return $debugInfo;
    }

}


