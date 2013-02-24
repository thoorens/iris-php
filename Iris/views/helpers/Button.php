<?php

namespace Iris\views\helpers;

/**
 * 
 *
 * Creates a button which links to a page or site
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Button extends _ViewHelper {

    public function help($message = \NULL, $url = '/', $tooltip = \NULL, $class = \NULL) {
        $subhelper = \Iris\Subhelpers\Link::GetInstance();
        return $subhelper->autoRender(4, 'button', $message, $url, $tooltip, $class);
    }

}

