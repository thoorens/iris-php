<?php

namespace ILO\views\helpers;

use \Iris\views\helpers\_ViewHelper;

/*
 * This file is part of IRIS-PHP.
 *
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IRIS-PHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IRIS-PHP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright 2011-2013 Jacques THOORENS
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $/**
 * This helper returns an icon 
 * 
 */

/**
 * Generates an icon link to a small presentation of IRIS or to the official website
 */
class GoAd extends _ViewHelper {

    /**
     * This helper is a singleton
     * 
     * @var boolean
     */
    protected static $_Singleton = \TRUE;

    /**
     * 
     * @param boolean $local If TRUE go the internal description, otherwise to the official site
     * @return type
     */
    public function help($local = \TRUE) {
        $client = new \Iris\System\Client();
        $lang = $client->getLanguage();
        $title = $this->_('Site powered by Iris-PHP', \TRUE);
        if($local)
            $url = "/!iris/index/index/$lang";
        else
            $url = 'http://irisphp.org';
        return $this->_view->link()->image('/!documents/file/resource/logos/IrisSmall.png',$title,$url,$title);
    }

}

?>
