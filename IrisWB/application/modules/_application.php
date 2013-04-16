<?php

namespace modules;

/**
 * Iris WorkBench
 * 
 * A standard abstract controller for application
 * 
 * @author Jacque THOORENS
 * @license GPL 3.0
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

}

?>
