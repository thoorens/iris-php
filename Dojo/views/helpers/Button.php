<?php
namespace Dojo\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Dojo version of Button
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Button extends _DojoHelper {

    
    protected function _init() {
        self::ActivateButton();
    }

    /**
     * 
     * @param type $message
     * @param type $url
     * @param type $tooltip
     * @param type $class
     * @param type $id
     * @return \Dojo\Subhelpers\Button
     */
    public function help($message = \NULL, $url = '/', $tooltip = \NULL, $class = \NULL, $id = \NULL) {
        $args = func_get_args();
        $subhelper = new \Dojo\Subhelpers\Button($args);
        return $subhelper;
    }
    
    /**
     * 
     */
    public static function ActivateButton(){
        $bubble = \Dojo\Engine\Bubble::getBubble('form_button');
        $bubble->addModule('dijit/form/Button');
        \Dojo\Manager::SetActive();
    }

}

