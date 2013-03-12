<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tutorial\Content;

use Iris\views\helpers\LoremIpsum;

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
 * Description of Item
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Item {

    private $_title;
    private $_text;
    private $_id;
    private $_duration=10;
    private $_page;
    private $_audio;
    private $_next;
    private $_previous;
    private $_type;

    public function createDummy($id) {
        $this->_title = "Title $id";
        $this->_text = LoremIpsum::GetInstance()->help(5, \FALSE);
        $this->_id = $id;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getPage() {
        return $this->_page;
    }

    /**
     * 
     * @param string $page
     * @return \Tutorial\Content\Item
     */
    public function setPage($page) {
        $this->_page = $page;
        return $this;
    }

    /**
     * 
     * @return string
     */    
    public function getTitle() {
        return $this->_title;
    }

    /**
     * 
     * @param string $title
     * @return \Tutorial\Content\Item
     */
    public function setTitle($title) {
        $this->_title = $title;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getText() {
        return $this->_text;
    }

    /**
     * 
     * @param string $text
     * @return \Tutorial\Content\Item
     */
    public function setText($text) {
        $this->_text = $text;
        return $this;
    }

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    public function getDuration() {
        return $this->_duration;
    }

    public function setDuration($duration) {
        $this->_duration = $duration;
        return $this;
    }

    public function getAudio(){
        return $this->_audio;
    }

    public function setAudio($audio) {
        $this->_audio = $audio;
        return $this;
    }

    public function getNext() {
        return $this->_next;
    }

    public function setNext($next) {
        $this->_next = $next;
        return $this;
    }

    public function getPrevious() {
        return $this->_previous;
    }

    public function setPrevious($previous) {
        $this->_previous = $previous;
        return $this;
    }

    public function getType() {
        return $this->_type;
    }

    public function setType($type) {
        $this->_type = $type;
        return $this;
    }



}

?>
