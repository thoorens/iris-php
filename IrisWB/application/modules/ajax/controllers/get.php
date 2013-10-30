<?php

namespace modules\ajax\controllers;

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
 * A serie of Ajax type messages to use with the demo in read controller
 */
class get extends \Iris\MVC\_AjaxController {

    /**
     * These message actions are meant to be read by Ajax requests 
     */
    public function message1Action() {
        $this->setAjaxType('text/html'); // default value
        $this->__message = 'This text has been read directly from the server. It contains UTF8 chars: <br/> <b>Françoise aime l\'été.</b>';
        //$this->_renderLiteral('Hello');
        $this->_renderScript('message');
    }

    public function message2Action() {
        $this->__message = 'This text has been read after five seconds from the server';
        $this->_renderScript('message');
    }

    public function message3Action() {
        $this->__message = 'This text has been sent by the server after the button 
            has been clicked (this action may be repeated)';
        $this->_renderScript('message');
    }

    public function message4Action() {
        $this->__message = 'This text has been sent by the server after the mouse cursor 
            has been moved over the button (this action may be repeated)';
        $this->_renderScript('message');
    }

    public function message5Action() {
        $this->__message = 'This text has been sent by the server after a message has been sent by a counter';
        $this->_renderScript('message');
    }

    public function message6Action($arg) {
        $this->__arg = $arg;
        $this->__message = "This text has been sent by the server after a message with argument '$arg'
            has been sent by a down counter.";
        $this->_renderScript('message6');
    }

    public function message7Action($arg1, $arg2) {
        $this->__arg1 = $arg1;
        $this->__arg2 = $arg2;
        $this->__message = "This text has been sent by the server after a message 
            with arguments '$arg1' and '$arg2' has been sent by an up counter.";
        $this->_renderScript('get_message7');
    }

    public function tabAction() {
        // using a partial, you can produce whatever output you want.
        $text1 = $this->callViewHelper('partial', 'random');
        // a simple helper can be directly called
        $text2 = $this->callViewHelper('loremIpsum', [101, 102, 103, 104]);
        // template text is rendered literally
        $text3 = '<h4>No evaluation</h4>{loremIpsum([10, 20, 30, 40]}';
        // use helper quote() to have quoted template
        $text4 = $this->callViewHelper('quote', '<h4>Good evaluation</h4>{loremIpsum([10, 20, 30, 40])}');
        $this->__data = [
            "Tab 1 " => $text1,
            "Tab 2" => $text2,
            'Tab 3' => $text3,
            'Tab 4' => $text4];
        $this->setAjaxType('text/js');
        $this->_renderScript('tabProg');
    }

    public function colorAction($target, $script = \TRUE) {
        $this->setAjaxType('text/js');
        if ($script) {
            $this->__target = $target;
            $this->_renderScript('_pink');
        }
        else {
            $this->_renderLiteral(<<< FUNC
   require(['dojo/dom-class'],function(domclass){
    domclass.add('$target','yellowcolor');
})
FUNC
            );
        }
    }

}
