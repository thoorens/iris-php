<?php

namespace modules\ajax\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 0.9 - beta
 * Description of get
 * 
 * @author jacques
 * @license not defined
 */
class get extends _ajax {

    public function messageAction() {
        $this->_ajaxMode('text/plaintext');
        echo "<h3>hello boy!</h3>";
        }
    
    

}
