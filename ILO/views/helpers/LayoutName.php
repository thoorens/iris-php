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
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
 */

/**
 * This helper displays an IRIS logo.
 *
 */
class LayoutName extends _ViewHelper {

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
    public function help() {
        return \Iris\Engine\Memory::Get('LAYOUT','Unknown');
    }

}


