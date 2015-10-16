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
 * This helper creates special inner style to accomodate custom color in
 * toolbar
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
 */
class ColorStyle extends \Iris\views\helpers\_ViewHelper {

    /**
     * This helper is a singleton
     * @var boolean
     */
    protected static $_Singleton = TRUE;

    /**
     * Displays a logo situated in ILO logos folder (by default Title80)
     * 
     * @param string $logo
     * @return string The HTML string for the logo
     */
    public function help($color) {
        if ($color != '#148') {
            $reverseColor = \Iris\System\Functions::GetComplementaryColor($color);
            $this->callViewHelper('styleLoader','controltb', <<<END
#iris_admintoolbar{
    background-color:$color;
    color:$reverseColor;
}
END
            );
        }
    }

}