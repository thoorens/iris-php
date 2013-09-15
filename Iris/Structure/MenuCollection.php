<?php



namespace Iris\Structure;

/*
 * This file is part of IRIS-PHP.
 *
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IRIS-PHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IRIS-PHP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright 2012 Jacques THOORENS
 */

/**
 * A collection of menus identified by their names
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
final class MenuCollection {

    private static $_Instance = null;
    protected $_instances = array();

    /**
     * Returns or creates the unique menu collection
     * (empty by default)
     * 
     * @return MenuCollection 
     */
    public static function GetInstance() {
        if (is_null(self::$_Instance)) {
            self::$_Instance = new MenuCollection();
        }
        return self::$_Instance;
    }

    /**
     * a private constructor for the singleton pattern
     */
    private function __construct() {
        
    }

    /**
     * Returns a menu by its name, if it doesn't exist,
     * it is created (a dummy one to avoid errors)
     * 
     * @param string $name The menu name
     * @return \Iris\Structure\Menu
     */
    public function getMenu($name='#def#') {
        if (isset($this->_instances[$name])) {
            return $this->_instances[$name];
        } else {
            return new Menu($name);
        }
    }

    /**
     * Add a menu in the collection (if only the name
     * is given, it is created empty and added
     * @param mixed $menu The menu or menu name to add.
     * 
     */
    public function addMenu($menu) {
        if (is_string($menu)) {
            $this->_instances[$menu] = new Menu($menu);
        } elseif ($menu instanceof Menu) {
            $this->_instances[$menu->getName()] = $menu;
        }
    }

    /**
     * Add a menu read in config/XX_menu.ini file to the menu collection
     * uses by the application.
     * 
     * @param string $menuName 
     */
    public function addNewMenu($menuName) {
        $param_menu = \Iris\Engine\Memory::Get('param_menu');
        if(!isset($param_menu[$menuName])){
            throw new \Iris\Exceptions\InternalException("An inexistent menu '$menuName' is been referenced.");
        }
        $this->addMenu(new Menu($menuName,$param_menu[$menuName]));
    }

}


