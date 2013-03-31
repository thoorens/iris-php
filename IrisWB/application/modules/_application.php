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
     * Defines a layout at application level
     */
    protected final function _applicationInit() {
        $this->_setLayout('application');
        $sequence = \Iris\Structure\_Sequence::GetInstance();
        \Iris\Engine\Memory::Set('sequence', $sequence);
        $this->_sequence = $sequence;
        $this->__Title = $this->_sequence->getCurrentDesc();
        // set the model for MD5
        \Iris\views\helpers\Signature::SetModel('TSequence', 'URL', 'Md5');
        \ILO\views\helpers\AdminToolBar::GetInstance()->setMenu(\TRUE);
    }

}

?>
