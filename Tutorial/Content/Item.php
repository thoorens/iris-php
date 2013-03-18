<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tutorial\Content;

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
    private $_mainTitle;
    private $_id;
    private $_duration = 10000;
    private $_image;
    private $_next;
    private $_previous;
    private $_type;
    private $_folder;
    private $_language;

    function __construct($type) {
        $this->_type = $type;
    }

    /**
     * 
     * @return string
     */
    public function getImage() {
        $image = \Iris\views\helpers\_ViewHelper::HelperCall('image', $this->_image);
        return $image;
    }

    /**
     * 
     * @param string $page
     * @return \Tutorial\Content\Item
     */
    public function setImage($page) {
        $this->_image = $page;
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
    public function getScriptName($page) {
        $id = $this->_id;
        return sprintf("%s/%02d/%s_%02d", $this->_folder, $id, $page, $id);
    }

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
        if ($id == 1) {
            // no action will be done
            $this->setPrevious(0);
        }
        else {
            $this->setPrevious($id - 1);
        }
        // if last, must be marked as such by markLast()
        $this->setNext($id + 1);
        return $this;
    }

    public function markLast(){
        $this->setNext(0);
        return $this;
    }
    
    public function getDuration() {
        return $this->_duration;
    }

    public function setDuration($duration) {
        $this->_duration = $duration;
        return $this;
    }

    public function getAudio() {
        $folder = $this->getFolder();
        $id = $this->_id;
        return sprintf('/tutorials/file/resource/%s/%02d/voice_%02d', $folder, $id, $id);
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

    public function setFolder($folder) {
        $this->_folder = $folder;
        return $this;
    }

    public function getFolder() {
        return $this->_folder;
    }

    public function getLanguage() {
        return $this->_language;
    }

    public function setLanguage($language) {
        $this->_language = $language;
        return $this;
    }

    public function getMainTitle() {
        return $this->_mainTitle;
    }

    public function setMainTitle($mainTitle) {
        $this->_mainTitle = $mainTitle;
        return $this;
    }


    
}

?>
