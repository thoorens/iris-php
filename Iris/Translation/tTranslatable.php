<?php

/* PHP 5.4 ONLY */

namespace Iris\Translation;

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
 * Trait to provide a standard implementation of methods required by
 * interface iTranslatable. It exists another trait named tSystemTranslatable
 * which makes the same job but has a different default value for the second
 * parameter of _ method. 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */

trait tTranslatable {
    
    
    /**
     * Translates a message
     * @param string $message
     * @param boolean $system
     * @return string 
     */
    public function _($message, $system=\FALSE) {
        $translator = $this->getTranslator($system);
        return $translator->translate($message);
    }

    public function getTranslator($system = \FALSE){
        if ($system) {
            $translator = \Iris\Translation\SystemTranslator::GetInstance();
        }
        else{
            $translator =\Iris\Translation\_Translator::GetInstance();
        }
        return $translator;
    }
    
}
