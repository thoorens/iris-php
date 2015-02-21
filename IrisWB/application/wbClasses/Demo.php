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
 * This class is for demonstration only.
 * It permits to create objects with 3 properties and is used in loop and
 * floop tests with an object
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Demo {

    /**
     * A public property to be accessed by loop and floop
     * @var string
     */
    public $color;
    
    
   /**
     * A public property to be accessed by loop and floop
     * @var int
     */
    public $price;
    
    /**
     * A public property to be accessed by loop and floop
     * @var int
     */
    public $length;

    /**
     * A get accessor for the color property
     * 
     * @return string
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * A get accessor for the price property
     * 
     * @return int
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * A get accessor for the length property
     * 
     * @return int
     */
    public function getLength() {
        return $this->length;
    }

    /**
     * A set accessor for the color property
     * 
     * @param string $color
     * @return \wbClasses\Demo (fluent interface)
     */
    public function setColor($color) {
        $this->color = $color;
        return $this;
    }

    /**
     * A set accessor for the price property
     * 
     * @param int $price
     * @return \wbClasses\Demo (fluent interface)
     */
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    /**
     * A set accessor for the length property
     * 
     * @param int $length
     * @return \wbClasses\Demo (fluent interface)
     */
    public function setLength($length) {
        $this->length = $length;
        return $this;
    }
    

}
