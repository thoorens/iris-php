<?php

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

namespace Dojo\views\helpers;

/**
 * Manages a "details" button or html zone which displays a hidden class
 * when clicked.
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class TitlePane extends _DojoHelper {

    const DURATION = 250;

    /**
     * The name of the container as seen by the view and javascript
     * 
     * @var string 
     */
    private $_name;
    private $_opened = 'false'; // JS value
    private $_duration;
    private $_number;
    private $_class = '';

    public function help($varName = NULL) {
        $this->_duration = self::DURATION;
        \Dojo\Engine\Bubble::getBubble('titlepane')->addModule("dijit/TitlePane")
                ->addModule("dojo/parser");
        if (!is_null($varName)) {
            $this->_view->$varName = $this;
        }
        $this->_name = $varName;
        return $this;
    }

    public function divTitle($titleText) {
        $this->_JS = \Iris\Users\Session::JavascriptEnabled();
        if ($this->_JS) {
            $class = $this->_class == '' ? '' : ' class ="'.$this->_class.'" ';
            $name = $this->_getName();
            $format = '<div id="%s" ' .
                    $class .
                    'data-dojo-type="%s" data-dojo-props="title: \'%s\'" ' .
                    'open="%s" duration="%s">' . "\n";
            if($this->_class!=''){
                $titleText = '<span>'.$titleText.'</span>';
            }
            return sprintf($format, $name, 'dijit/TitlePane', $titleText, $this->_opened, $this->_duration);
        }
        else {
            return "<h6>$titleText</h6>\n<div>";
        }
    }

    private function _getName() {
        if ($this->_number == 0) {
            $name = $this->_name;
        }
        else {
            $name = sprintf('%s_%02d', $this->_name, $this->_number);
        }
        $this->_number++;
        return $name;
    }

    public function endDiv() {
        $this->_duration = self::DURATION;
        $this->_opened = 'false';
        return "</div>\n";
    }

    /**
     * An alias of endiv
     * 
     * @return string
     */
    public function endMaster() {
        return $this->endDiv();
    }

    public function open($open = 'true') {
        $this->_opened = $open;
        return $this;
    }

    public function setId($name) {
        $this->_name = $name;
        $this->_number = 0;
        return $this;
    }

    public function setDuration($duration) {
        $this->_duration = $duration;
        return $this;
    }

    public function setClass($class) {
        $this->_class = $class;
        return $this;
    }


}
