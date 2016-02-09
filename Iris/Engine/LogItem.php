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

