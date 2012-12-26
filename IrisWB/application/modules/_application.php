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
        // TextSequence is deprecated and left only for information
        //\Iris\Structure\_Sequence::$DefaultSequenceType = "\\Workbench\\TextSequence";
        $this->_setLayout('app');
        $sequence = \workbench\TextSequence::GetInstance();
        \Iris\Engine\Memory::Set('sequence', $sequence);
        $this->_sequence = $sequence;
        $this->__Title = $this->_sequence->getCurrentDesc();
    }

}

?>
