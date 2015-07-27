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
 * The Dojo version of the Form class
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Form extends \Iris\Forms\Elements\Form {

    public function __construct($name, $action = \NULL, $method = 'post') {
        parent::__construct($name, $action, $method);
        \Dojo\Engine\Bubble::GetBubble('dojoForm')
                ->addModule("dojo/parser")
                ->addModule('dijit/form/Form');
    }

    protected function _formTag() {
        return sprintf('<form name="%s" id="%s" data-dojo-type="dijit.form.Form" action="%s" method="%s" %s>', $this->_name, $this->_name, $this->_action, $this->_method, $this->_renderAttributes());
    }

}
