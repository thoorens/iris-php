<?php
namespace Iris\Structure;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A menu is a set of menu items corresponding to a context
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Menu {

    /**
     * The menu name 
     * @var string 
     */
    protected $_name = NULL;

    /**
     * The collection of menu items
     * @var MenuItem[] 
     */
    protected $_items = array();
    protected $_defaultUri = null;

    /**
     * Create a new menu (and fill it with data if needed)
     * 
     * @param string $name The menu name
     * @param \Iris\SysConfig\Config $data The data to initialise the menu
     */
    public function __construct($name, $data = NULL) {
        $this->_name = $name;
        if ($data instanceof \Iris\SysConfig\Config) {
            $this->_addData($data);
        }
    }

    /**
     * Process a config structure to fill a menu (recursively)
     * 
     * @param \Iris\SysConfig\Config $data 
     */
    protected function _addData($data) {
        foreach ($data as $key => $value) {
            $keys = explode('.', $key);
            $this->_addData1($this, $keys, $value);
        }
    }

    /**
     * Treat a complex key and a value (the key may be multi level)
     * 
     * @param string[] $keys Each item correspond to a level
     * @param type $value The value
     */
    protected function _addData1(Menu $base, array $keys, $value) {
        list($id, $field) = $keys;
        if (!isset($base->_items[$id])) {
            $base->addItem($id);
        }
        if (count($keys) == 2) {
            $field = ucfirst($field);
            $method = "set$field";
            $base->_items[$id]->$method($value);
        }
        else {
            array_shift($keys);
            $this->_addData1($base->_items[$id], $keys, $value);
        }
    }

    /**
     * Return the menu name
     * 
     * @return string 
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Add a menu item to a menu. If a name is given, an empty item
     * is created and is given the name
     * 
     * @param mixde $item the item or item name
     */
    public function addItem($item) {
        if ($item instanceof MenuItem) {
            $this->_items[$item->getUri()] = $item;
        }
        elseif (is_string($item)) {
            $newItem = new MenuItem($item);
            $this->_items[$newItem->getUri()] = $newItem;
        }
    }

    public function getSubmenu($name) {
        $submenu = $this->getItem($name);
        $menu = new Menu($name);
        foreach ($submenu->_items as $item) {
            $menu->addItem($item);
        }
        return $menu;
    }

//    /**
//     * Returns a menu as an associative array
//     * 
//     * @param boolean $simplify
//     * @return array 
//     */
//    public function getData() {
//        $data = array();
//        $acl = \Iris\Users\Acl::GetInstance();
//        foreach ($this->_items as $item) {
//            if ($this->_defaultUri == $item->getUri()) {
//                $item->setDefault();
//            }
//            $uri = $item->getUri();
//            $externalLink = strpos($uri, 'http:') === 0;
//            if (!$externalLink) {
//                $uri = explode('/', $uri . '///');
//                $resource = '/' . $uri[1] . '/' . $uri[2];
//                $action = $uri[3];
//            }
//            if ($externalLink) {
//                \Iris\Log::Debug('Autorisation external',\Iris\Engine\Debug::ACL);
//                $data[] = $item->asArray();
//            }
//            if ($acl->hasPrivilege($resource, $action)) {
//                \Iris\Log::Debug('Autorisation ACL',\Iris\Engine\Debug::ACL);
//                $data[] = $item->asArray();
//            }
//        }
//        return $data;
//    }

    public function asArray() {
        $data = [];
        foreach ($this->_items as $key=>$item) {
            if ($this->_defaultUri == $item->getUri()) {
                $item->setDefault();
            }
            $item->setId($key);
            $data[] = $item->asArray();
        }
        return $data;
    }

    /**
     * Memorize the default menu by its uri (module/controller/action)
     * for later processing
     * 
     * @param name $uri 
     */
    public function setDefaultItem($uri) {
        $this->_defaultUri = $uri;
    }

    /**
     * Returns a menu item by its name
     * 
     * @param string $name
     * @return \Iris\Structure\MenuItem
     * @throws \Iris\Exceptions\MenuException
     */
    public function getItem($name) {
        if (!isset($this->_items[$name])) {
            throw new \Iris\Exceptions\MenuException("No menu item like $name in " . $this->getName());
        }
        return $this->_items[$name];
    }

}

