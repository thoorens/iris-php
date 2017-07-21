<?php

namespace Vendors\views\helpers;

use Vendors\W3CSS\Settings;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * This helper will provides mechanisms to display or modify a text 
 * using Dojo. For now, only fadein/fadeout are implemented. These methods are
 * linked with a publish/suscribe mechanism.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class W3Link extends \Iris\views\helpers\_ViewHelper {

    public function help() {
        switch (Settings::$DownloadMode) {
            case Settings::DISTANT:
                $url = Settings::$W3CURL;
                break;
            case Settings::LOCAL:
                $url = Settings::$LocalURL;
                $session = \Iris\Users\Session::GetInstance();
                $magicPath = $session->getValue('MagicPath', []);
                if (count($magicPath) == 0) {
                    $path[1] = 'Internal';
                    $_SESSION['MagicPath'] = $path;
                }
                break;
            case Settings::PUBLICDIR:
                $url = Settings::$PublicURL;
                break;
        }
        return '<link  href="' . $url . '" rel="stylesheet" type="text/css" />';
    }

}
