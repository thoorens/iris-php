<?php

namespace Iris\MVC;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A special view who ensure a global layout for the page. 
 * Generally reused by many actions, controllers and 
 * modules.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Layout extends View implements \Iris\Design\iSingleton {

    /**
     * The unique instance
     * @var Layout
     */
    private static $_Instance = \NULL;

    /**
     * A protection for constructor
     * @var boolean
     */
    private static $_Security = \TRUE;

    /**
     * Type of view
     * 
     * @var string
     */
    protected static $_ViewType = 'layout';

    /**
     *
     * @var type View
     */
    private $_mainView = NULL;

    /**
     *
     * @var type 
     */
    private $_subViews = array();

    /**
     * The constructor throws an exception if an instance already exists
     * (no direct call)
     * 
     * @param type $actionView 
     */
    public function __construct() {
        if (self::$_Security) {
            throw new \Iris\Exceptions\InternalException("Singleton Layout can't be directly instancied");
        }
        self::$_Security = \TRUE;
    }

    /**
     * Set a scriptname to the layout
     * after stripping out 'lo_' prefix if necessary
     * 
     * @param type $name 
     */
    public function setViewScriptName($name) {
        if (strpos($name, 'lo_') === 0) {
            $name = substr($name, 3);
        }
        parent::setViewScriptName($name);
    }

    /**
     * Returns the name of the directory where to find the script
     * corresponding to the layout
     * 
     * @return string 
     */
    public function viewDirectory() {
        return "layouts/lo";
    }

    /**
     * Setter for the mainview (used by the controller)
     * 
     * @param View $mainView 
     */
    public function setMainView($mainView) {
        $this->_mainView = $mainView;
    }

    /**
     * Getter for the view
     * 
     * @return View
     */
    public function getMainView() {
        return $this->_mainView;
    }

    /**
     * Specific accessor for instance variable: a layout tries to 
     * get its own, and then look in main view.
     *  
     * @param type $name
     * @return type 
     */
    public function __get($name) {
        // it may have been copied by _getProperties
        if(isset($this->_properties[$name])){
            $value = $this->_properties[$name];
        } else{
        $value = $this->_mainView->$name;
        }
        return $value;
    }

    /**
     * Fills the properties with the properties from the main view.
     * 
     */
    protected function _copyMainViewProperties() {
        foreach ($this->_mainView->_properties as $name => $value) {
            $this->$name = $value;
        }
        return $this->_properties;
    }

    /**
     *
     * @param int $num
     * @param type $view 
     */
    public function addSubView($num, $view) {
        $this->_subViews[$num] = $view;
    }

    /**
     *
     * @param type $num
     * @return type 
     */
    public function getSubView($num) {
        if (!isset($this->_subViews[$num])) {
            return \NULL;
        }
        return $this->_subViews[$num];
    }

    /**
     *
     * @return Layout
     */
    public static function GetInstance() {
        if (is_null(self::$_Instance)) {
            self::$_Security = \FALSE;
            self::$_Instance = new Layout();
        }
        return self::$_Instance;
    }

}

