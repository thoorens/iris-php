<?php

namespace modules;
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
 * @copyright 2011-2013 Jacques THOORENS
 */

/**
 * Iris WorkBench
 * 
 * A standard abstract controller for application
 * 
 * @author Jacque THOORENS
 */
class _application extends \Iris\MVC\_Controller {

    /**
     *
     * @var workbenchTextSequencee
     */
    protected $_sequence;

    /**
     * Defines a layout at application level. Does some initialisation.
     */
    protected final function _applicationInit() {
        $this->_hasMD5();
        $this->_setLayout('application');
        $sequence = \Iris\Structure\_Sequence::GetInstance();
        \Iris\Engine\Memory::Set('sequence', $sequence);
        $this->_sequence = $sequence;
        $this->__Title = $this->_sequence->getCurrentDesc();
        // set the model for MD5
        \Iris\views\helpers\Signature::SetModel('TSequence', 'URL', 'Md5');
        \ILO\views\helpers\AdminToolBar::GetInstance()->setMenu(\TRUE);
    }

    /**
     * Defines the use and mode of the MD5 signature
     * 
     * @param boolean $flag
     */
    protected function _hasMD5($flag = \TRUE){
        \Iris\Engine\Memory::Set('hasSignature', $flag);
        if($flag){
            \Iris\views\helpers\Signature::SetModel('TSequence', 'URL', 'Md5');
        }
        else{
            \iris\views\helpers\Signature::SetModel(\NULL, '', '');
        }
        
    }
    
    /**
     * Specifies there is no MD5 signature for this screen
     */
    protected function _noMd5() {
        $this->_hasMD5(\FALSE);
    }

    /**
     * This methods permits to have a view script composed by
     * a pure HTML file (for example a Dojo demo file taken from Internet)
     * <ul>
     * <li>no MD5 signature</li>
     * <li>no runtime dureation measurement</li>
     * <li>no layout</li>
     * </ul> 
     */
    protected function _nolayout(){
        \Iris\SysConfig\Settings::DisableMD5Signature();
        \Iris\SysConfig\Settings::DisableDisplayRuntimeDuration();
        $this->_setLayout(\NULL);
    }
    
}

?>
