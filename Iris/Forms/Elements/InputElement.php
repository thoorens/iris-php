<?php
namespace Iris\Forms\Elements;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * Various class of input. Some of them are not defined in HTML 4,
 * mais implicite validators have been addedd
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class InputElement extends \Iris\Forms\_Element {

    /**
     * 
     * @param type $name
     * @param type $type
     * @param type $options
     */
    public function __construct($name, $type, $options = array()) {
        parent::__construct($name, 'input', $options);
        $this->_subtype = $type;
        switch ($type) {
            // file is now a separated class
            case 'hidden':
                $this->_labelPosition = self::NONE;
                break;
            case 'image':
            case 'reset':
            case 'submit':
            case 'button':
                $this->_labelPosition = self::NONE;
                $this->_canDisable = FALSE;
                break;
            case 'date':
                $this->addValidator(new \Iris\Forms\Validators\Date);
                $this->_subtype = 'date';
                break;
            case 'number':
            case 'text':
            case 'password':
            default :
                break;
        }
    }

    

}


