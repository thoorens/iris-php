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
class Link extends _Link {

    
    public function render($message, $url = '/', $tooltip = \NULL, $class = \NULL) {
        // no JS compatibility
        $attributes = $this->_getAttributes($tooltip, $class);
        return sprintf('<a href="%s" %s >%s</a>', $url, $attributes, $message);
    }

    


}

?>
