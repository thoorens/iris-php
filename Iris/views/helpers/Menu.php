<?php

namespace Iris\views\helpers;

use \Iris\Structure as is;

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
 *
 */

/**
 * Helper for rendering menus as a html <ul><li> recursive list
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * @todo definir un subhelper
 */
class Menu extends _ViewHelper {

    protected $_activeClass;
    protected $_mainTag;
    protected $_withId = \FALSE;
    protected $_all = \FALSE;

    /**
     * Inits the active class and main tag names from settings
     */
    protected function _init() {
        $this->_activeClass = \Iris\SysConfig\Settings::GetMenuActiveClass();
        $this->_mainTag = \Iris\SysConfig\Settings::GetMenuMainTag();
    }

    /**
     * Translates a message (no system translation)
     * @param string $message
     * @param boolean $system
     * @return string 
     */
    public function _($message, $system = \FALSE) {
        $translator = $this->getTranslator($system);
        return $translator->translate($message);
    }

    /**
     * Accessor to modify the active class of the menu
     * 
     * @param string $activeClass
     * @return \Iris\views\helpers\Menu for fluent interface
     */
    public function setActiveClass($activeClass) {
        $this->_activeClass = $activeClass;
        return $this;
    }

    /**
     * Acessor to modify main tag of the menu
     * 
     * @param string $mainTag
     * @return \Iris\views\helpers\Menu for fluent interface
     */
    public function setMainTag($mainTag) {
        $this->_mainTag = $mainTag;
        return $this;
    }

    public function enableId($value = \TRUE) {
        $this->_withId = $value;
        return $this;
    }

    /**
     * This setting permits to ignore the visible attribute
     * 
     * @param boolen $all
     * @return \Iris\views\helpers\Menu
     */
    public function setAll($all = \TRUE) {
        $this->_all = $all;
        return $this;
    }

    /**
     * Permits to add a same id to all URI of the menu
     * 
     * @var string
     */
    protected $_specialId = '';

    /**
     * Redundant but important : Menu are not singleton (so _specialId is
     * limited to a menu).
     * 
     * @var boolean
     */
    protected static $_Singleton = FALSE;

    /**
     * Get a complete menu (identified by its name) from
     * an initialized menu collection and render it
     * as a imbricated unordered list.
     * If first parameter is null, returns the helper object.
     * If first parameter is a null string, returns a null string
     * 
     * @param string $name
     * @param boolean $recursive
     * @return \Iris\views\helpers\Menu|string
     */
    public function help($name = '#def#', $recursive = \FALSE) {
        if (is_null($name)) {
            return $this;
        }
        if ($name == '') {
            return '';
        }
        return $this->render($name, $recursive);
    }

    /**
     * 
     * @param string $name
     * @param boolean $recursive
     * @return string
     */
    public function render($name = '#def#', $recursive = \FALSE) {
        $menu = is\MenuCollection::GetInstance()->getMenu($name);
        $data = $menu->asArray();
        \Iris\Log::Debug("ACL ------------------------------------------------", \Iris\Engine\Debug::ACL);
        return $this->_render($data, $recursive);
    }

    /**
     * A recursive HTML rendering of a hierarchical menu
     * as an array
     * 
     * @param string[] $data
     * @return string 
     */
    protected function _render(array $data, $recursive) {
        $items = array();
        $acl = \Iris\Users\Acl::GetInstance();
        foreach ($data as $item) {
            if (($this->_all or $item['visible']) and $this->_testPrivilege($acl, $item)) {
                $items[] = $this->_renderItem($item, $recursive);
            }
        }
        if (count($items)) {
            $tag = $this->_mainTag;
            return "<$tag>\n" .
                    implode("\n", $items)
                    . "\n</$tag>\n";
        }
        else {
            return '';
        }
    }

    protected function _renderItem($item, $recursive) {
        $active = $this->_activeClass;
        $uri = $this->_simplifyUri($item['uri']);
        if ($this->_specialId != '') {
            $uri .= '/' . $this->_specialId;
        }
        $label = $this->_($item['label']);
        $title = $this->_($item['title']);
        $class = $item['default'] ? "class=\"$active\"" : '';
        $id = $item['id'];
        $id = $this->_withId ? "id=\"$id\"" : '';
        $submenu = '';
        if (isset($item['submenu']) and $recursive) {
            $submenu = $this->_render($item['submenu'], $recursive);
        }
        if ($item['uri'] != '') {
            $text = "<li $class $id><a href=\"$uri\" title=\"$title\" $class>$label</a>";
            $text .= $submenu . "</li>";
        }
        else {
            $text = "<li $class><span title=\"$title\" $class>$label</span>";
            $text .= $submenu . "</li>";
        }
        return $text;
    }

    /**
     * To have nice URI, the default values are stripped out:
     * the module main, the index controller and the index action
     *
     * @return string 
     */
    protected function _simplifyUri($oldURI) {
        // treat only relative addresses
        $newURI = $oldURI;
        if (strpos($oldURI, 'http://') !== 0) {
            $uri = explode('/', $oldURI);
            if (count($uri) == 4) {
                // suppress initial blank
                array_shift($uri);
                // suppress main module
                if ($uri[0] == 'main') {
                    array_shift($uri);
                }
                // suppress index action
                if ($uri[count($uri) - 1] == 'index') {
                    array_pop($uri);
                }
                // suppress index controller
                if ($uri[count($uri) - 1] == 'index') {
                    array_pop($uri);
                }
                $newURI = '/' . implode('/', $uri);
            }
        }
        if ($this->_specialId != '') {
            $newURI .= '/' . $this->_specialId;
        }
        return $newURI;
    }

    public function setSpecialId($id) {
        $this->_specialId = $id;
        return $this;
    }

    /**
     *
     * @param type $acl
     * @param type $item
     * @return type 
     */
    private function _testPrivilege($acl, $item) {
        $uri = $item['uri'];
        $externalLink = strpos($uri, 'http:') === 0;
        if (!$externalLink) {
            $uri = explode('/', $uri . '///');
            $resource = '/' . $uri[1] . '/' . $uri[2];
            $action = $uri[3];
        }
        if ($externalLink) {
            return TRUE;
        }
        elseif ($acl->hasPrivilege($resource, $action)) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    public function getItem($name, $item){
        $menu = is\MenuCollection::GetInstance()->getMenu($name);
        return $menu->getItem($item);
    }
}
