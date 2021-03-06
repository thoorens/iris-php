<?php
namespace ILO\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Display a ToolBar for administrative purpose AT DEVELOPMENT TIME ONLY
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $/**
 */
class AdminToolBar extends \Iris\views\helpers\_ViewHelper {

    /**
     * If TRUE, the toolbar is only prepared at runtime and refreshed later
     * by an ajax routine. May be changed directly by this instruction in a config file:
     * \ILO\views\helpers\AdminToolBar::$AjaxMode = \FALSE;
     * 
     * @var boolean 
     */
    public static $AjaxMode = \TRUE;

    /**
     * This helper is a singleton
     * 
     * @var boolean
     */
    protected static $_Singleton = \TRUE;

    /**
     * If true, the toolbar will have a menu for all actions
     * @var boolean 
     * (if possible)
     */
    private $_menu = \TRUE;

    /**
     * Returns the HTML text for the toolbar or the reference for later use
     * 
     * @param boolean $display If TRUE, 
     * @param int $color
     * @return mixed
     */
    public function help($display = \TRUE, $color = '#148') {
        if (is_null($display) or $display == '') {
            return $this;
        }
        return $this->render($display, $color);
    }

    /**
     * Returns the HTML text for the toolbar
     * 
     * @param boolean $display If TRUE display at once
     * @param int $color The background color for the toolbar (dark blue by default)
     * @return string
     */
    public function render($display = \TRUE, $color = '#148') {
        $this->callViewHelper('styleLoader','/!documents/file/css/admintoolbar.css');
        if (!\Iris\Engine\Mode::IsProduction() and $display) {
            if (\Iris\SysConfig\Settings::$AdminToolbarAjaxMode) {
               $html = $this->_ajaxRender();
            }
            else {
                $html = $this->callViewHelper('islet','islToolbar', [$color, $this->_menu], 'index', '!admin');
            }
            return $html;
        }
    }

    /**
     * Accessor for the menu variable (if true will display a menu with all defined actions)
     * See /!admin/structure to generate
     * 
     * @param boolean $menu
     */
    public function setMenu($menu) {
        $this->_menu = $menu;
    }

    /**
     * Loads CSS file and Ajax command to load the toolbar and prepares the RuntimeDuration to be
     * managed through the session.
     * 
     * @return string
     */
    private function _ajaxRender() {
        \Iris\Time\RuntimeDuration::$DisplayMode = \Iris\Time\RuntimeDuration::AJAX;
        $this->callViewHelper('ajax')->placeReplace()->get('/!admin/ajax/toolbar/1', 'iris_admintoolbar');
        return <<< HTML
<div id="iris_admintoolbar" class="atb_white">
    Admin toolbar should be here. If you don't see it, something is wrong with Ajax. 
</div>

HTML;
        ;
    }

}

