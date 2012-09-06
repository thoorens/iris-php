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
 * interface iTranslatable. As traits are not present in PHP 5.3, 
 * the code may be copied in the classes that would use tTranslatable.
 * Actually, it is copied in all classes that need it.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * This file is PHP 5.4 only
 */
trait tTranslatable {
    
    /* Beginning of trait code */
    
    /**
     * Translates a message
     * @param string $message
     * @param boolean $system
     * @return string 
     */
    public function _($message, $system=\FALSE) {
        if ($system) {
            $translator = \Iris\Translation\SystemTranslator::GetInstance();
            return $translator->translate($message);
        }
        $translator = $this->getTranslator();
        return $translator->translate($message);
    }

    /**
     *
     * @staticvar \Iris\Translation\_Translator $translator
     * @return \Iris\Translation\_Translator
     */
    public function getTranslator() {
        static $translator = NULL;
        if (is_null($translator)) {
            $translator = \Iris\Translation\_Translator::GetCurrentTranslator();
        }
        return $translator;
    }
    
    /* end of trait code */
    
}

?>
