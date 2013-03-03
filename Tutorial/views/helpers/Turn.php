<?php

namespace Tutorial\views\helpers;

defined('CRLF') or define('CRLF', "\n");
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
 */

/**
 * Produces a JSON string intended to turn on and out a series of object
 * at different time (used by Ajax requests) 
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Turn extends \Iris\views\helpers\_ViewHelper{
    
    protected static $_Singleton = \TRUE;
    
    private $_events = array();
    
    
    private $_standardDuration = 500;
    
    public function help(){
        return $this;
    }
    
    public function on($target, $time, $duration = \NULL){
        $this->_event('turnon', $target, $time, $duration, 0);
    }
    
    public function out($target, $time, $duration = \NULL){
        $this->_event('turnout', $target, $time, $duration, 1);
    }
    
    public function render(){
        $json = CRLF.'['.CRLF;
        $fieldNames = ['dojofunction', 'node', 'starttime', 'duration', 'opacity'];
        foreach($this->_events as $event){
            $fields = array();
            for($i=0; $i<5;$i++){
                $fields[] = $fieldNames[$i]." : '".$event[$i]."'";
            }
            $eventText[] = '{'.CRLF.implode(','.CRLF,$fields).CRLF.'}';
        }
        return $json. implode(','.CRLF, $eventText).CRLF.']'.CRLF;
    }
    
    public function jsRender(){
        $render = $this->render();
        $this->_view->javascriptLoader('turnevents', <<<JS
    turnEvents =
$render
;
JS
            );
    }
    
    private function _event($cmd, $target, $time, $duration, $opacity){
        if(is_null($duration)){
            $duration = $this->_standardDuration;
        }
        $this->_events[] = [$cmd, $target, $time, $duration,$opacity];
    }

    public function setStandardDuration($standardDuration) {
        $this->_standardDuration = $standardDuration;
        return $this;
    }


}
