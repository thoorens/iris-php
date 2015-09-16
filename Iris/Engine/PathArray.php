<?php
namespace Iris\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */


/**
 * A PathArrayh is an ArrayObject with special methods
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ 
 */
class PathArray extends \ArrayObject {

    /**
     * Add an element to the beginning of the array
     * 
     * @param string $element
     */
    public function prepend($element) {
        $old = $this->getArrayCopy();
        array_unshift($old, $element);
        $this->exchangeArray($old);
    }

    /**
     * Delete an element from the end of the array
     * 
     * @return string
     */
    public function pop() {
        $old = $this->getArrayCopy();
        $last = array_pop($old);
        $this->exchangeArray($old);
        return $last;
    }

    /**
     * Insert an element before the last two ones in the array
     * 
     * @param string $element
     */
    public function insertSecondLast($element) {
        $last = $this->pop();
        $last0 = $this->pop();
        $this->append($element);
        $this->append($last0);
        $this->append($last);
    }

    /**
     * Insert an element after the first present in the array
     * 
     * @param string $element
     */
    public function insertSecond($element) {
        $old = $this->getArrayCopy();
        $first = array_shift($old);
        array_unshift($old, $element);
        array_unshift($old, $first);
        $this->exchangeArray($old);
    }

}
