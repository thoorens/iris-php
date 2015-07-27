<?php

namespace Dojo\Forms\Validators;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A validator for required element. In Dojo, a first validation is done
 * in the client brwser before sending the data to the server.
 * 
 */
class Required extends \Iris\Forms\Validators\Required {

    protected static $_Html5 = FALSE;

    /**
     *
     * @param \Iris\Forms\_Element $element 
     */
    public function setElement($element) {
        //$element->setRequired('true');
        if (isset($this->_hasDijit)) {
            $element->addDijitAttribute('required', 'true');
        }
        else {
            parent::setElement($element);
        }
    }

}
