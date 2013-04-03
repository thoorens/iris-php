<?php



namespace modules\main\controllers;

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
 * In work bench tests some layout configurations
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class layout extends _main{
    
    
    public function basicAction($number = NULL){
        if(is_null($number)){
            $number = 33;
        }
        // has 3 subcontrollers and 3 islets
        $this->_setLayout('testlayout');
        $this->__bodyColor = 'ORANGE1';
        // loads subcontrollers required by layout
        $this->registerSubcontroller(1,'subMain','index');
        $this->registerSubcontroller(2,'subTestLayout','index','testLayout');
        $this->registerSubcontroller(3,'subDemo','index','!workbench');
        $tooltip =  $this->callViewHelper('dojo_toolTip');
        
        $this->getSubcontroller(1)->setParameters(array($number));
        $this->getSubcontroller(2)->setParameters(array($number));
        $this->getSubcontroller(3)->setParameters(array($number));
        $this->__tooltip = $tooltip;
        $this->toView('tooltip', $tooltip, 2);
        $this->toView('tooltip', $tooltip, 3);
        $this->_toMemory('tooltip', $tooltip);

        }
        
     
    public function actionAction() {
        $this->_setLayout('action');
        $this->__bodyColor = 'ORANGE1';
    }
    
    public function moduleAction() {
       
    }
    
}

?>
