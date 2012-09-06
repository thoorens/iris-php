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
class Menu extends _ViewHelper implements \Iris\Translation\iTranslatable {

    
    protected static $_ActiveClass = 'active';
    
    /**
     * Get a complete menu (identified by its name) from
     * an initialized menu collection and render it
     * as a imbricated unordered list
     * 
     * @param string $name the menu name in the collection
     * @return string
     */
    public function help($name = '#def#') {
        if($name == "") return;
        $menu = is\MenuCollection::GetInstance()->getMenu($name);
        $data = $menu->asArray();
        \Iris\Log::Debug("ACL ------------------------------------------------", \Iris\Engine\Debug::ACL);
        return $this->_render($data);
    }

    public function setActiveClass($class){
        self::$_ActiveClass = $class;
    }
    
    /**
     * A recursive HTML rendering of a hierarchical menu
     * as an array
     * 
     * @param array $data
     * @return string 
     */
    private function _render(array $data) {
        $items = array();
        $acl = \Iris\Users\Acl::GetInstance();
        $active = self::$_ActiveClass;
        foreach ($data as $item) {
            if ($item['visible'] and $this->_testPrivilege($acl, $item)) {

                $uri = $this->_simplifyUri($item['uri']);
                $label = $this->_($item['label']);
                $title = $this->_($item['title']);
                $class = $item['default'] ? "class=\"$active\"" : '';
                $submenu = '';
                if (isset($item['submenu'])) {
                    $submenu = $this->_render($item['submenu']);
                }
                if ($item['uri'] != '') {
                    $text = "<li $class><a href=\"$uri\" title=\"$title\" $class>$label</a></li>";
                    $text .= $submenu;
                }
                else {
                    $text = "<li $class><span title=\"$title\" $class>$label</span>";
                    $text .= $submenu."</li>";
                }
                $items[] = $text;
            }
        }
        if (count($items)) {
            return "<ul>\n" .
                    implode("\n", $items)
                    . "\n</ul>\n";
        }
        else {
            return '';
        }
    }

    /**
     * To have nice URI, the default values are stripped out:
     * the module main, the index controller and the index action
     *
     * @return string 
     */
    private function _simplifyUri($olduri) {
        // treat only relative addresses
        if (strpos($olduri, 'http://') !== 0) {
            $uri = explode('/', $olduri);
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
                return '/' . implode('/', $uri);
            }
        }
        return $olduri;
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
    /* Beginning of trait code */
    
    /**
     * Translates a message
     * @param string $message
     * @param boolean $system
     * @return string 
     */
    public function _($message, $system=\FALSE) {
        if ($system) {
            $translator = \Iris\Translation\SystemTranslator::GetInstance();
            return $translator->translate($message);
        }
        $translator = $this->getTranslator();
        return $translator->translate($message);
    }

    /**
     *
     * @staticvar \Iris\Translation\_Translator $translator
     * @return \Iris\Translation\_Translator
     */
    public function getTranslator() {
        static $translator = NULL;
        if (is_null($translator)) {
            $translator = \Iris\Translation\_Translator::GetCurrentTranslator();
        }
        return $translator;
    }
    
    /* end of trait code */
    

}

