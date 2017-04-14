<?php

namespace Iris\Translation;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * Trait to provide a standard implementation of methods required by
 * interface iTranslatable. It is an exact copy of the tTranslatable trait,
 * except for the default value of the second parameter of the _ method.
 * The copy has been done for performance reason.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
trait tSystemTranslatable {

    /**
     * Translates a message
     * @param string $message
     * @param boolean $system
     * @return string 
     */
    public function _($message, $system = \TRUE) {
        $translator = $this->getTranslator($system);
        return $translator->translate($message);
    }

    /**
     *
     * @staticvar \Iris\Translation\_Translator $translator
     * @return \Iris\Translation\_Translator
     */
    public function getTranslator($system = \TRUE) {
        static $translator = NULL;
        if (is_null($translator)) {
            if ($system) {
                $translator = \Iris\Translation\SystemTranslator::GetInstance();
            }
            else {
                $translator = \Iris\Translation\_Translator::GetInstance();
            }
        }
        return $translator;
    }

}
