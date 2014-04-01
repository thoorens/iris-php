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

    /**
     *
     * @param string $label Button label
     * @param string $url target URL
     * @param string $tooltip tooltip when mousevoer
     * @param string $class class of the link
     * @param string $id id of the link
     * @return string
     */
    public function help($label = \NULL, $url = \NULL, $tooltip = \NULL, $class = \NULL, $id = \NULL) {
        $subhelper = \Iris\Subhelpers\Link::GetInstance();
        return $subhelper->autoRender('button', $label, $url, $tooltip, $class, $id);
    }

}

