<?php

namespace ILO\views\helpers;

use \Iris\views\helpers\_ViewHelper;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 * 
 * @copyright 2011-2015 Jacques THOORENS
 */

/*
 *  This helper displays, if possible, a hierarchical menu containing
 * all the actions known in the application
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
 */
\defined('CRLF') or \define('CRLF', "\n");

class Menu extends \Iris\views\helpers\_ViewHelper {

    protected static $_Singleton = \TRUE;

    /**
     * A reference to the Scanner class, offering information about the modules, controllers
     * and actions detected.
     *  
     * @var \Iris\Admin\Scanner
     */
    private $_scanner;

    public function help() {
        // If database file doesn't exists, no display
        if (!file_exists(IRIS_INTERNAL)) {
            return '';
        }
        $this->_scanner = new \Iris\Admin\Scanner;
        return $this->_render();
    }

    /**
     * Makes the rendering (as an HTML list of lists of lists) of the menu
     * 
     * @return the HTML code for all the menu
     */
    private function _render() {
        $actions = $this->_('Available actions');
        $title = $this->_('Links to all action in the application');
        $html = '<div id="iris_atb">' . CRLF;
        $html .= '  <ul class="menu" id="base">' . CRLF;
        $html .= "    <li class=\"sub\"><a id=\"iatb_main\" title =\"$title\">$actions</a>" . CRLF;
        //@todo a button ?
        //$html .= "    <button class=\"sub\"><a id=\"iatb_main\" title =\"$title\">$actions</a>" .'</button>'. CRLF;
        $html .= '      <ul class="level1"><!-- modules-->' . CRLF;
        foreach ($this->_scanner->getModules() as $module) {
            $html .= $this->_renderModule($module);
        }
        $html .= '        </li><!--module-->' . CRLF;
        $html .= '      </ul><!-- modules -->' . CRLF;
        $html .= '    </li><!-- main -->' . CRLF;
        $html .= '  </ul><!-- base -->' . CRLF;
        $html .= '</div><!-- iris_atb-->' . CRLF;
        return $html;
    }

    /**
     * Renders a module as an HTML list of controllers
     * 
     * @param \Iris\DB\_Entity $module
     * @return string The HTLM text for the module
     */
    private function _renderModule($module) {
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

    /**
     * Renders the actions of a controller, as an HTML list
     * 
     * @param string $moduleName
     * @param \Iris\DB\_Entity $controller
     * @return string
     */
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

}


