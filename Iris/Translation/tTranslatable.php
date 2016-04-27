<?php
namespace Iris\Translation;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
