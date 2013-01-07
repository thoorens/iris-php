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
class Button extends _Link {

    
    public function render($message, $url = '/', $tooltip = \NULL, $class = \NULL) {
        // no JS compatibility
        $attributes = $this->_getAttributes($tooltip, $class);
        if (!\Iris\Users\Session::JavascriptEnabled() or ! self::$JavaForce) {
            $href = is_null($url) ? '' : "href=\"$url\"";
            if ($this->_oldBrowser(self::$OldBrowser)) {
                $class .= '_old_nav';
                return "<a $href $attributes>&nbsp;$message&nbsp;</a>\n";
            }
            else {
                // Bouton dans un lien 
                return "<a $href>" .
                        "<button $attributes>$message</button></a>\n";
            }
        }
        else {
            // Bouton avec Javascript
            $onclick = is_null($url) ? '' : "onclick=\"javascript:location.href='$url'\"";
            return "<button $attributes $onclick>$message</button>\n";
        }
    }

    


}

?>
