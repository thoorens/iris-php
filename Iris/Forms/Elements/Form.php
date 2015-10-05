<?php



namespace Iris\Forms\Elements;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A concrete form, extending the abstract _Form. This concrete
 * class is intended as a classic HTML 4.0 implementation of form.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Form extends \Iris\Forms\_Form{

    
    protected function _formTag() {
        return sprintf('<form name="%s" id="%s" action="%s" method="%s" %s>',
                $this->_name,$this->_name,$this->_action,$this->_method,$this->_renderAttributes());
    }

}


