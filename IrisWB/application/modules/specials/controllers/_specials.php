<?php

namespace modules\specials\controllers;

/**
 * Project : srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of _specials
 * 
 * @author 
 * @license 
 */
class _specials extends \modules\_application {

    
    /**
     * This method can contain module level
     * settings
     */
    protected final function _moduleInit() {
        
        $this->_setLayout('main');
        //$this->registerSubcontroller(1, 'menu');
        $this->callViewHelper('subtitle','Databases');
   
        
    }

}
