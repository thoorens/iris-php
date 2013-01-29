<?php

namespace ILO\views\helpers;

use \Iris\views\helpers\_ViewHelper;

/*
 * This file is part of IRIS-PHP.
 *
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your li) any later version.
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
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
  /**
 * This helper displays a IRIS logo.
 *
 */

\defined('CRLF') or \define('CRLF', "\n");

class Menu extends _ViewHelper {

    protected static $_Singleton = \TRUE;

    /**
     *
     * @var \Iris\Admin\Scanner
     */
    private $_scanner;

    public function help() {
        $this->styleLoader('/!documents/file/resource/css/menu.css');
        $this->_scanner = new \Iris\Admin\Scanner;
        $modules = $this->_scanner->getModules();
        $html = $this->_render();
        return $html;
    }

    private function renderModule($module) {
        $moduleName = $module->Name;
        $html = "        <li class=\"sub\"><a>$moduleName (m)</a>" . CRLF;
        $html .= '          <ul class="level2">' . CRLF;
        foreach ($this->_scanner->getControllers($module->id) as $controller) {
            $html .= "             <li class=\"sub\"><a>$controller->Name (c)</a>\n";
            $html .= $this->_actions($moduleName, $controller);
            $html .= '            </li>' . CRLF;
        }
        $html .= '          </ul>' . CRLF;
        return $html;
    }

    private function _actions($moduleName, $controller) {
        $html = '              <ul class="level3">';
        $controllerName = $controller->Name;
        foreach ($this->_scanner->getActions($controller->id) as $action) {
            $actionName = $action->Name;
            $url = "$moduleName/$controllerName/$actionName";
            $html .= "               <li class=\"sub\"><a href=\"/$url\" title=\"$url\">$actionName (a)</a></li>\n";
        }
        $html .= '              </ul>' . CRLF;
        return $html;
    }

    private function _render() {
        $actions = $this->_('Available actions');
        $title = $this->_('Links to all action in the application');
        $html = '<div id="iris_atb">' . CRLF;
        $html .= '  <ul class="menu" id="base">' . CRLF;
        $html .= "    <li class=\"sub\"><a id=\"main\" title =\"$title\">$actions</a>" . CRLF;
        $html .= '      <ul class="level1"><!-- modules-->' . CRLF;
        foreach ($this->_scanner->getModules() as $module) {
            $html .= $this->renderModule($module);
        }
        $html .= '        </li><!--module-->' . CRLF;
        $html .= '      </ul><!-- modules -->' . CRLF;
        $html .= '    </li><!-- main -->' . CRLF;
        $html .= '  </ul><!-- base -->' . CRLF;
        $html .= '</div><!-- iris_atb-->' . CRLF;
        return $html;
    }

}

?>
