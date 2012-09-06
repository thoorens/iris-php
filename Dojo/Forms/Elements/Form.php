<?php



namespace Dojo\Forms\Elements;

/*
 * This file is part of IRIS-PHP.
 *
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IRIS-PHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IRIS-PHP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright 2012 Jacques THOORENS
 */

 /**
 * The Dojo version of the Form class
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

class Form extends \Iris\Forms\Elements\Form {

    public function __construct($name, $action=NULL, $method='post') {
        parent::__construct($name, $action, $method);
        $dojoManager = \Dojo\Manager::GetInstance()->addRequisite("dojoForm", "dijit.form.Form");
    }

    protected function _formTag() {
        return sprintf('<form name="%s" id="%s" dojoType="dijit.form.Form" action="%s" method="%s" %s>', $this->_name, $this->_name, $this->_action, $this->_method, $this->_renderAttributes());
    }

}

?>
