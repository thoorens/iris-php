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
 * Implementations of the submit element of a form
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Submit extends \Iris\Forms\_Element {

    use \Dojo\Forms\tDojoDijit;
    
    /**
     * This Element uses a closing tab
     * @var boolean
     */
    protected static $_EndTag = \TRUE;

    
    protected $_valueAsAttribute = \TRUE;
    

    /**
     *
     * @param string $name name of the element
     * @param string $type HTML type (input) or subtype (date)
     * @param type $options any option to add to HTML code
     */
    public function __construct($name, $type, $options = []) {
        parent::__construct($name, 'button', $options);
        $this->_canDisable = FALSE;
        $this->_labelPosition = self::NONE;
        $this->_subtype = 'submit';
        $this->setDijitType('dijit/form/Button');
        \Dojo\Engine\Bubble::GetBubble('formButton')
                ->addModule("dijit/form/Button")
                ->addModule("dojo/dom");
    }
    
    

}


