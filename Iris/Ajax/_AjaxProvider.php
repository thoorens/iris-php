<?php

namespace Iris\Ajax;

 /*
  * This file is part of IRIS-PHP, distributed under the General Public License version 3.
  * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
  * More details about the copyright may be found at
  * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
  *  
  * @copyright 2011-2015 Jacques THOORENS
  */

/**
 * This abstract class provides all ajax functions as abstracts and need
 * to be overwritten by a concrete class such as Dojo\Ajax\Provider
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
abstract class _AjaxProvider extends \Iris\Subhelpers\_LightSubhelper {

    const BEFORE = 'before';
    const AFTER = 'after';
    const REPLACE = 'replace';
    const ONLY = 'only';
    const FIRST = 'first';
    const LAST = 'last';
    const HTML = 0;
    const JS = 1;
    const JSON = 2;
    const XML = 3;
    const CSS = 4;

    private static $_TypeDef = [
        self::HTML => ['text/html', 'text'],
        self::JS => ['text/javascript', 'javascript'],
        self::JSON => ['text/json', 'json'],
        self::XML => ['text/xml', 'xml'],
        self::CSS => ['text/css', 'text'],
    ];

    /**
     * The subhelper static reference for simulating singleton behaviour.
     * 
     * @var static
     */
    protected static $_Instance = \NULL;
    protected $_debugDisplayObject = \NULL;
    protected $_placeMode = self::LAST;
    protected $_messageArgumentNumber = 2;

    /**
     * The Ajax default provider (Dojo) may be changed.
     * 
     * @param string $library
     * @deprecated since version 1.0 (use Settings to modify)
     */
    public static function SetDefaultAjaxLibrary($library) {
        \Iris\SysConfig\Settings::$DefaultAjaxLibrary = $library;
    }

    /**
     * Gets the default library for Ajax
     * @return string
     * @deprecated since version 1.0 (use Settings to know)
     */
    public static function GetDefaultAjaxLibrary() {
        return \Iris\SysConfig\Settings::$DefaultAjaxLibrary;
    }

    protected function _getRenderer() {
        return \Iris\MVC\_Helper::HelperCall('ajax');
    }

    public function setDebugDisplayObject($output) {
        $this->_debugDisplayObject = $output;
        return $this;
    }

    /**
     * Magic method to add some methods to the helper<ul>
     * <li>placeBefore, placeAfter...
     * </ul>
     * 
     * @param string $name
     * @param mixed[] $arguments
     * @return \Dojo\Ajax\Provider for fluent interface
     */
    public function __call($name, $arguments) {
        if (substr($name, 0, 5) == 'place') {
            $this->_placeMode = strtolower(substr($name, 5));
        }
        return $this;
    }

    /**
     * Direct get request
     * 
     * @param string  $url the URL to execute
     * @param string $target idname of the object to modify
     * @param string $type MIME type for the request (text by default)
     */
    abstract public function get($url, $target, $type = \NULL);

    abstract public function getExec($object, $url, $type = \NULL);

    /**
     * The request is made on clic on an object provider
     * 
     * @param string $object The object clicked
     * @param string  $url the URL to execute
     * @param string $target idname of the object to modify
     * @param string $type MIME type for the request (text by default)
     */
    abstract public function onClick($object, $url, $target, $type = \NULL);

    /**
     * The request is made when an event is fired by an objetc provider
     * 
     * @param string $event The event name
     * @param string $object The object provider name
     * @param string  $url the URL to execute
     * @param string $target idname of the object to modify
     * @param string $type MIME type for the request (text by default)
     */
    abstract public function onEvent($event, $object, $url, $target, $type = \NULL);

    /**
     * The request is made after a delay
     * 
     * @param int $delay The delay in milliseconds
     * @param string  $url the URL to execute
     * @param string $target idname of the object to modify
     * @param string $type MIME type for the request (text by default)
     */
    abstract public function onTime($delay, $url, $target, $type = \NULL);

    /**
     * The request is made upon reception of a message (through the topic
     * publish and subscribe mechanism). Two parameters sent with the message
     * are taken into account.
     * 
     * @param string $messageName The name of the message
     * @param string  $url the URL to execute
     * @param string $target idname of the object to modify
     * @param string $type MIME type for the request (text by default)
     */
    abstract public function onMessage($messageName, $url, $target, $type = \NULL);

    /**
     * Changes the number of arguments for the sent messages (bu default 2)
     * 
     * @param int $messageArgumentNumber
     */
    public function setMessageArgumentNumber($messageArgumentNumber) {
        $this->_messageArgumentNumber = $messageArgumentNumber;
    }

    /**
     * Generates the required parameters for URL and javascript function 
     * during message management.
     * 
     * @return array(string)
     */
    protected function _generateParameters() {
        for ($i = 1; $i <= $this->_messageArgumentNumber; $i++) {
            $args[] = "p$i";
        }
        return [implode("+'/'+", $args), implode(',', $args)];
    }

    protected abstract function _debug($param);

    protected abstract function _getAction($type, $target, $place);

    protected function _getTypeHandler($type = \NULL) {
        return $this->_getInternalType($type, 1);
    }

    protected function _getMimeType($type = \NULL) {
        return $this->_getInternalType($type, 0);
    }

    private function _getInternalType($type, $offset) {
        if (is_null($type)) {
            $type = self::HTML;
        }
        return self::$_TypeDef[$type][$offset];
    }

}
