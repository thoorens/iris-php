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
 * Description of _AjaxProvider
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
    
    
    
    abstract public function get($url, $target, $type =\NULL);
    abstract public function onClick($object, $url, $target, $type =\NULL);
    abstract public function onEvent($event, $object, $url, $target, $type =\NULL);
    abstract public function onTime($delay, $url, $target, $type = \NULL);
    abstract public function onMessage($messageName, $url, $target, $type = \NULL);
}
?>
