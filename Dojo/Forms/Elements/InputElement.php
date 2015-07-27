<?php

namespace Dojo\Forms\Elements;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Dojo version of InputElement
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class InputElement extends \Iris\Forms\Elements\InputElement {

    use \Dojo\Forms\tDojoDijit;

    /**
     * Dojo uses some different names for classes of elements
     * @var string[]
     */
    protected $_equivalence = array(
        'text' => 'TextBox',
        'date' => 'DateTextBox',
        'time' => 'TimeTextBox',
        'password' => 'TextBox',
        'radio' => 'RadioBox',
        'checkbox' => 'CheckBox',
        'currency' => 'CurrencyTextBox',
    );

    /**
     *
     * @param string $name name of the element
     * @param string $type HTML type (input) or subtype (date)
     * @param type $options any option to add to HTML code
     */
    public function __construct($name, $type, $options = array()) {
        parent::__construct($name, $type, $options);
        if (isset($this->_equivalence[$type])) {
            $dojoName = $this->_equivalence[$type];
            $this->setDijitType("dijit.form.$dojoName");
            \Dojo\Engine\Bubble::GetBubble("dijit.form.$dojoName")
                    ->addModule("dojo/parser")
                    ->addModule("dijit/form/$dojoName");
        }
    }

    public function setSize($size) {
        if (isset($this->_attributes['style'])) {
            $oldStyle = $this->_attributes['style'];
        }
        else {
            $oldStyle = '';
        }
        $this->_attributes['style'] = $oldStyle . " width:" . $size . "em;";
        return $this;
    }

    
}


