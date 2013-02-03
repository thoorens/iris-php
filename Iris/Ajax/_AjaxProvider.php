<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Iris\Ajax;

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
 * @copyright 2011-2013 Jacques THOORENS
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
abstract class _AjaxProvider extends \Iris\Subhelpers\_Subhelper{
    
    protected static $_DefaultProvider = '\Dojo\Ajax\Provider';
    
    protected static $_Instance = \NULL;
    
    protected $_placeMode = self::LAST;
    
    /**
     * The Ajax default provider (Dojo) may be changed.
     * 
     * @param string $provider
     */
    public static function setDefaultProvider($provider){
        self::$_DefaultProvider = $provider;
        }
        

    
    protected function _provideRenderer() {
        return \Iris\MVC\_Helper::HelperCall('ajax');
    }
    
    /**
     * Magic method to add some methods to the helper<ul>
     * <li>placeBefore, placeAfter...
     * </ul>
     * 
     * @param string $name
     * @param array $arguments
     * @return \Dojo\Ajax\Provider for fluent interface
     */
    public function __call($name, $arguments) {
        if(substr($name, 0,5)=='place'){
            $this->_placeMode = strtolower(substr($name,5));
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
    abstract public function get($url, $target, $type =\NULL);
    
    /**
     * The request is made on clic on an object provider
     * 
     * @param string $object The object clicked
     * @param string  $url the URL to execute
     * @param string $target idname of the object to modify
     * @param string $type MIME type for the request (text by default)
     */
    
    abstract public function onClick($object, $url, $target, $type =\NULL);
    /**
     * The request is made when an event is fired by an objetc provider
     * 
     * @param string $event The event name
     * @param string $object The object provider name
     * @param string  $url the URL to execute
     * @param string $target idname of the object to modify
     * @param string $type MIME type for the request (text by default)
     */
    
    abstract public function onEvent($event, $object, $url, $target, $type =\NULL);
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
}
?>
