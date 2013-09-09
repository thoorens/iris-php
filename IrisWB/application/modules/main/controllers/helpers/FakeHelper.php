<?php



namespace Iris\controllers\helpers;

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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * A helper which creates view variables just for fun
 * 
 */
class FakeHelper extends _ControllerHelper{
    
    public function help(){
        $this->toView('var11','value 11');
        $this->__('var12','value 12');
        $this->__(\NULL, [
           'var13' => 'value 13',
            'var14' => 'value 14'
        ]);
        // View is protected : ILLEGAL
        //$this->_controller->_view->var15 = 'value 15';
        // Legal, but creates a variable in controller not in view
        $this->__view16 = 'value 16';
        
        // A complex init
        $controller = \Iris\Engine\Response::GetDefaultInstance()->makedController;
        $controller->__var17 = 'value 17';
        
    }
}

?>
