<?php

namespace Tutorial\Translation;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
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
 * @deprecated since version 2015
 * 
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


