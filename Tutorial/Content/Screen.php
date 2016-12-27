<?php

namespace Tutorial\Content;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A screen is a small part of a tutorial having text, images and animations, 
 * as well as a sound track
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 * @deprecated since version 2015
 */
class Screen {

    /**
     * The screen has only a view tab
     */
    const VIEW = 1;
    /**
     * The screen has only a text tab
     */
    const TEXT = 2;
    /**
     * The screen has both a view and a text tab
     */
    const TEXTVIEW = 3;
    /**
     * The screen is simply an image
     */
    const IMAGE = 4;
    /**
     * The screen is an auto generated table of content
     */
    const TOC = 8;
    
    /**
     * The prefix for the view script corresponding to a text tab
     */
    const TEXTPREFIX = 'text';
    
    /**
     * Theprefix for the view script corresponding to a view tab
     */
    const VIEWPREFIX = 'page';
    
    /**
     * The title of the tutorial to which the screen belongs
     * 
     * @var string
     */
    private $_tutorialTitle;
    /**
     * The numeric sequential id of the screen
     * @var int
     */
    private $_id;
    /**
     * The duration of the playing of the screen (has to match the audio file)
     * 
     * @var int
     */
    private $_duration = 10000;
    /**
     * The image name in case of the screen of type IMAGE
     * @var type 
     */
    private $_image;
    /**
     * The id of the next screen (if 0 : none)
     * @var int
     */
    private $_next;
    /**
     * The id of the previous screen in the tutorial (if 0 : none)
     * @var int
     */
    private $_previous;
    /**
     * The type of the screen as described in IMAGE, TOC, VIEW ...
     * @var int
     */
    private $_type;
    /**
     * The folder in which the resources for the screen are placed
     * @var string
     */
    private $_folder;
    private $_language;
    /**
     * Each screen has a title
     * 
     * @var string
     */
    private $_title;

    /**
     * 
     * @param type $type
     */
    public function __construct($type) {
        $this->_type = $type;
    }

    /**
     * 
     * @return string
     */
    public function getImage() {
        $folder = sprintf('/tutorials/file/resource/%s/%02d', $this->_folder, $this->_id);
        $image = \Iris\views\helpers\_ViewHelper::HelperCall('image', [$this->_image[0],\NULL,$this->_image[1],$folder]);
        return $image;
    }

    /**
     * 
     * @param string $page
     * @return \Tutorial\Content\Screen for fluent interface
     */
    public function setImage($page) {
        $this->_image = $page;
        return $this;
    }

    /**
     * Gets the subtitle of the screen
     * 
     * @return string
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
     * Sets the subtitle of the scren
     * 
     * @param string $title
     * @return \Tutorial\Content\Screen for fluent interface
     */
    public function setTitle($title) {
        $this->_title = $title;
        return $this;
    }

    /**
     * 
     * @return string
     */
    /**
     * Generates the name of the view script corresponding to the text or
     * view tab of the screen 
     * 
     * @param string $prefix
     * @return string
     */
    public function getAutoScriptName($prefix) {
        $id = $this->_id;
        return sprintf("%s/%02d/%s_%02d", $this->_folder, $id, $prefix, $id);
    }

    /**
     * Gets the id number of the screen
     * 
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * Sets the id number of the screen
     * 
     * @param int $id
     * @return \Tutorial\Content\Screen for fluent interface
     */
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
    
    /**
     * Returns the number of milliseconds of the screen duration
     * @return int
     */
    public function getDuration() {
        return $this->_duration;
    }

    /**
     * Sets the duration of the playing of the screen
     * 
     * @param int $duration The duration in milliseconds
     * @return \Tutorial\Content\Screen for fluent interface
     */
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

    /**
     * 
     * @param type $next
     * @return \Tutorial\Content\Screen for fluent interface
     */
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

    /**
     * 
     * @param type $type
     * @return \Tutorial\Content\Screen for fluent interface
     */
    public function setType($type) {
        $this->_type = $type;
        return $this;
    }

    /**
     * Sets the folder in which to find the screen resources
     * 
     * @param string $folder
     * @return \Tutorial\Content\Screen  for fluent interface
     */
    public function setFolder($folder) {
        $this->_folder = $folder;
        return $this;
    }

    /**
     * Gets the the folder in which to find the screen resources
     * @return string
     */
    public function getFolder() {
        return $this->_folder;
    }

    public function getLanguage() {
        return $this->_language;
    }

    /**
     * 
     * @param type $language
     * @return \Tutorial\Content\Screen for fluent interface
     */
    public function setLanguage($language) {
        $this->_language = $language;
        return $this;
    }

    /**
     * Returns the title of the tutorial to which the screen belongs
     * 
     * @return string
     */
    public function getTutorialTitle() {
        return $this->_tutorialTitle;
    }

    /**
     * Sets the title of the tutorial to which the screen belongs
     * 
     * @param string $mainTitle The title of the tutorial
     * @return \Tutorial\Content\Screen for fluent interface
     */
    public function setTutorialTitle($mainTitle) {
        $this->_tutorialTitle = $mainTitle;
        return $this;
    }


    
}


