<?php

namespace modules\ajax\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 0.9 - beta
 * Description of index
 * 
 * @author jacques
 * @license not defined
 */
class index extends _ajax {

    public function indexAction() {
        // this Title var is required by the default layout defined in _ajax
        $this->__Title = $this->_view->welcome(1);
        $this->_view->ILO_adminToolBar('')->setMenu(\FALSE);
    }
    
    

}
