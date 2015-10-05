<?php

namespace modules\subhelpers\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * @todo Write the description  of the class
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */

class EventTest implements \Iris\Time\iEvent{
    
    /**
     *
     * @var int
     */
    private $_id;
    
    /**
     *
     * @var int
     */
    private $_type;
    
    /**
     *
     * @var \Iris\Time\Date
     */
    private $_date;
    
    /**
     *
     * @var string
     */
    private $_description;
    
    
    function __construct($id, $type, $date, $description) {
        $this->_id = $id;
        $this->_type = $type;
        $this->_date = new Date($date);
        $this->_description = $description;
    }

    public function getDate() {
        return $this->_date;
    }

    public function getDescription() {
        return $this->_description;
    }

    public function display(){
        $text = '<b>'.$this->_date->toString('j M').'</b>:<br>';
        $text .= $this->getDescription();
        return $text;
    }
    
    public function getId() {
        return $this->_id;
    }

    public function getType() {
        return $this->_type;
    }

    public function isTakenPlace($date) {
        
    }

    public function __toString() {
        return (string)$this->_description;
    }
}






