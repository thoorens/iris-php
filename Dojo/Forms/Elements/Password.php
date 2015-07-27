<?php

namespace Dojo\Forms\Elements;

use \Iris\Forms as ifo;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/*
 * Password input element
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

class Password_____ extends \Iris\Forms\Elements\InputElement {

    use \Dojo\Forms\tDojoDijit;

    /**
     *
     * @param string $name name of the element
     * @param string $type HTML type (input) or subtype (date)
     * @param type $options any option to add to HTML code
     */
    public function __construct($name, $subType, $options = array()) {
        parent::__construct($name, 'password', $options);
        $type = "dojox.form.PasswordValidator";
        \Dojo\Engine\Bubble::GetBubble($type)
                ->addModule('dojox/form/PasswordValidator');
        $this->setDijitType($type);
        $this->setPwType($subType);
    }

}
