<?php

namespace modules\forms\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 * 
 * This is part of the WorkBench fragment
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

abstract class _forms extends \modules\_application {

    const TYPE = 0;
    const LABEL = 1;

    protected $_entityManager;

    

    /**
     * This method can contain module level
     * settings
     */
    protected final function _moduleInit() {
        // You should modify this demo layout
        $this->_setLayout('post3');
        $this->__bodyColor = 'ORANGE3';
        $this->callViewHelper('subtitle', 'Forms');
        $this->__H_Hand = ['/forms/autohand/','','HTML form and hand made form'];
        $this->__H_Ini = ['/form/autoini/','','HTML form and ini form'];
        $this->__H_MD = ['/forms/automodel/','','HTML form and model form'];
        $this->__D_Hand = ['/forms/autohand/','','Dojo form and hand made form'];
        $this->__D_Ini = ['/form/autoini/','','Dojo form and ini form'];
        $this->__Do_MD = ['/forms/automodel/','','Dojo form and model form'];
        // necessary ????
        $this->__base = '/forms/autoform/';
        $this->__changeURL='/forms/forms/change';
    }

    /* ------------------------------------------------------------------------------
     * Specific methods for automodel, autoini and autohand controllers
     * ------------------------------------------------------------------------------
     */
    protected function _chooseEM(&$dbManagerType){
       if(empty($dbManagerType)){
            $dbManagerType = \Iris\Engine\Superglobal::GetSession('dbini', 'default');
        }
    }
    /**
     * Forces the DB manager type according to URL argument
     * 
     * @param string $dbManagerType
     */
    protected function _forceDBManageType($dbManagerType) {
        if ($dbManagerType !== 'default') {
            $session = \Iris\Users\Session::GetInstance();
            $session->dbini = $dbManagerType;
        }
    }
    
    

    
}
