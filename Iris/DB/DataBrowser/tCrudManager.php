<?php

namespace Iris\DB\DataBrowser;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This trait offers a controller the power to manage a table (CRUD operation)
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
trait tCrudManager {

    /**
     * By default, the script is common to all action and is named 'editall'. 
     * It can be set to another name or set to NULL. In that case, each script
     * takes the action name (update, delete, create, read).
     * 
     * The simple way to change it consists in a single line in _init() or 
     * customize() methods
     *      $this->_changeViewScript('whatyouwant');
     * or
     *       $this->_changeViewScript(\NULL);
     * 
     * @var string
     */
    protected $_commonViewScript = 'editall';
    protected $_crudDirectory = _Crud::CRUD_DIRECTORY;

    /**
     * This magic method simulates the four createAction, updateAction....
     * methods using _Crud class.
     * 
     * @param string $actionName
     * @param type $parameters
     */
    public final function __callAction($actionName, $parameters) {
        $shortAction = preg_replace('/(.*)\_.*Action/', '$1', $actionName);
        $this->_changeViewScript($shortAction);
        $this->_preCustomize($shortAction);
        \Iris\DB\DataBrowser\_Crud::DispatchAction($this, $actionName, $parameters, $this->_commonViewScript, $this->_crudDirectory);
        $this->_postCustomize($shortAction);
    }

    /**
     * Change the common view script for all 4 actions (if null, the default
     * update, delete, create, read will be used)
     * 
     * @param string $scriptName The action script name 
     */
    protected final function _changeViewScript($scriptName) {
        if (is_null($this->_commonViewScript)) {
            $this->_commonViewScript = $scriptName;
        }
    }

    protected final function _changeCrudDirectory($directory) {
        $this->_crudDirectory = $directory;
    }

    

    /**
     * By overwriting this method, one can modify one or many implicit methods of the
     * CRUD manager or do whatever.
     * This method is fired BEFORE the action dispatching
     * 
     * The parameter has this aspect: create, update, delete, read
     * 
     * @param string $actionName one among create/read/update/delete
     */
    protected function _preCustomize($actionName) {
        
    }
    
    /**
     * By overwriting this method, one can modify one or many implicit methods of the
     * CRUD manager or do whatever else (e.g. /IrisWB/application/modules/manager/controllers/screens.php)
     * This method is fired AFTER the action dispatching
     * 
     * The parameter has this aspect: create, update, delete, read
     * 
     * @param string $actionName one among create/read/update/delete
     */
    protected function _postCustomize($actionName) {
        
    }
    
    /**
     * A default error action to be overwritten.
     * 
     * @param int $num
     */
    public function errorAction($num=0) {
        \Iris\Engine\Debug::Abort("There is an error $num");
    }

}

