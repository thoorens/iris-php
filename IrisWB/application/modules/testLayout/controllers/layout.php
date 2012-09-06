<?php



namespace modules\testLayout\controllers;

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
class layout extends _testLayout{
    
    public function preDispatch() {
        
        
        }

    
    public function indexAction($number=NULL){
               
        
        if(is_null($number)){
            $number = rand(2,10);
        }
        // has 3 subcontrollers and 3 islets
        $this->_setLayout('testlayout2');
        // loads subcontrollers required by layout
        $this->getSubcontroller('subMain','index')->register(1);
        $this->getSubcontroller('subMask','index')->register(4);
        $this->subcontrollers[1]->setParameters(array($number));
        $this->getSubcontroller('subTestLayout','index','testLayout')->register(2);
        $this->getSubcontroller('subDemo','index','!workbench')->register(3);
        $tooltip =  $this->_view->dojo_toolTip();
        
        $this->subcontrollers[1]->setParameters(array($number));
        $this->subcontrollers[2]->setParameters(array($number));
        $this->subcontrollers[3]->setParameters(array($number));
        $this->subcontrollers[4]->setParameters(array($number));
        $this->__tooltip = $tooltip;
        $this->toView('tooltip', $tooltip, 2);
        $this->toView('tooltip', $tooltip, 3);
        $this->toView('tooltip', $tooltip, 4);
        $this->_toMemory('tooltip', $tooltip);
        
    }
    
    public function applayoutAction(){
        $sequence = \workbench\TextSequence::GetInstance();
        $this->__title = $sequence->getCurrentDesc();
    }
}

?>
