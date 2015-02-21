<?php

namespace wbClasses;

/*
 * This file is part of IRIS-PHP distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2015 Jacques THOORENS
 */


/**
 * This class is in use in the code part of the Work bench. It provides
 * a repository for containing different parts of the screen description:
 * it will contain model, controller, layout and view parts
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Part {
use \Iris\System\tRepository;
    
    const MODEL = 1;
    const CONTROLLER = 2;
    const LAYOUT = 3;
    const VIEW = 4;
    
    private $_title;
    
    private $_content;
    
    private $_container;
    
    private $_type;
    
    /**
     * 
     * @param type $name
     * @return Part
     */
    static function GetPart($name){
        return self::_GetObject($name);
    }
    
    

    public function getContent() {
        return $this->_content;
    }

    public function getContainer() {
        return $this->_container;
    }

    public function addContent($content) {
        $this->_content[] = $content;
        return $this;
    }

    public function setContainer($container) {
        $this->_container = $container;
        return $this;
    }

    public function getTitle() {
        return $this->_title;
    }

    public function addTitle($title) {
        $this->_title[] = $title;
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

