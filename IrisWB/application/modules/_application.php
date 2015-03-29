<?php

namespace modules;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
     * To reactivate ACL control, simply override the value in the
     * due controllers. The method _verifyAcl() has been modified to
     * permit a customized functionning.
     * This behaviour has been implemented to ease the writing of new
     * controllers in this application. IT SHOULD NOT BE REPRODUCED 
     * IN AN ACTUAL APPLICATION
     * 
     * @see \modules\acl\controllers\_acl abstract controller
     * 
     * @var boolean
     */
    protected $_aclIgnore = \TRUE;

    /**
     *
     * @var workbenchTextSequencee
     */
    protected $_sequence;

    /**
     * Defines a layout at application level. Does some initialisation.
     */
    protected final function _applicationInit() {
        $this->_setLayout('application');
        $sequence = \Iris\Structure\_Sequence::GetInstance();
        \Iris\Engine\Memory::Set('sequence', $sequence);
        $this->_sequence = $sequence;
        $this->__Title = $this->_sequence->getCurrentDesc();
        // set the model for MD5
        \Iris\views\helpers\Signature::SetModel('models_internal\TSequences', 'URL', 'Md5');
        \ILO\views\helpers\AdminToolBar::GetInstance()->setMenu(\TRUE);
        \Iris\Errors\Settings::SetController('/Error');
        $this->callViewHelper('title', 'Iris Work Bench');
        $this->__specialScreen = \FALSE;
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
    protected function _nolayout() {
        \Iris\SysConfig\Settings::DisableMD5Signature();
        \Iris\SysConfig\Settings::DisableDisplayRuntimeDuration();
        $this->_setLayout(\NULL);
    }

    /**
     * IrisWB has ACL definitions, but they are ignored in most part of the
     * application, except in parts were the are tested
     * 
     * @return \NULL
     */
    protected function _verifyAcl() {
        if ($this->_aclIgnore) {
            return;
        }
        else {
            return parent::_verifyAcl();
        }
    }
    
    protected function _specialScreen($advices){
        $this->__specialScreen = \TRUE;
        if(!is_array($advices)){
            $advices = [$advices];
        }
        $this->__advices = $advices;
    }

}
