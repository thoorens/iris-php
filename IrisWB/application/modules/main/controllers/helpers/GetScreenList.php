<?php

namespace Iris\controllers\helpers;

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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * A helper making some come calculation to fill part of the page
 * 
 */
class GetScreenList extends _ControllerHelper {

    /**
     * 
     * @param type $sequence
     * @return type
     */
    public function help($sequence) {
        $list = $sequence->getStructuredSequence();
        foreach ($list as $key => $value) {
            if (is_array($value)) {
                array_walk($value, array($this, '_keepDescription'));
                $newList[$key] = $value;
            }
            else {
                $newList[$key] = $this->_keepDescription($value);
            }
        }
        return $newList;
    }

    private function _keepDescription(&$value, $dummy = \NULL) {
        list($description, $dum) = explode('|', $value . '|');
        $value = $description;
        return $value;
    }

}

?>
