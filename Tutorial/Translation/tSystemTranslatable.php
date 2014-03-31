<?php


namespace Tutorial\Translation;

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
 * This trait is only valid in PHP 5.4 and is provided as an
 * experimental tool. It's code has to be cut and copied
 * in classes which "use" it.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $// * 
 * This file is PHP 5.4 only
 */

trait tSystemTranslatable {
    
    /**
     * Translates a message
     * @param string $message
     * @param boolean $system
     * @return string 
     */
    public function _($message, $system=\TRUE) {
        if ($system) {
            $translator = \Tutorial\Translation\SystemTranslator::GetInstance();
        }
        else{
            $translator = \Iris\Translation\_Translator::GetInstance();
        }
        return $translator->translate($message);
    }
    
}


