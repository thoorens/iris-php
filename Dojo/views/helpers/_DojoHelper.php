<?php

namespace Dojo\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A superclass mother of all Dojo helpers
 * 
* 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _DojoHelper extends \Iris\views\helpers\_ViewHelper{
    
    /**
     *
     * @var \Dojo\Manager
     */
    protected $_manager;
    
    protected function _subclassInit() {
        $this->_manager = \Dojo\Manager::GetInstance();
    }

    /**
     * This version of getTranslator overrides the trait and tries to
     * get the translations from  \Dojo\Translation\SystemTranslator
     * 
     * @staticvar null $translator
     * @param boolean $system by default the system translation is taken
     * @return \Dojo\Translation\SystemTranslator
     */
    public function getTranslator($system = \TRUE) {
        static $translator = NULL;
        if (is_null($translator)) {
            if ($system) {
                $translator = \Dojo\Translation\SystemTranslator::GetInstance();
            }
            else {
                $translator = \Iris\Translation\_Translator::GetInstance();
            }
        }
        return $translator;
    }
}


