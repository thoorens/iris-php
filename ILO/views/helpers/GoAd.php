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
 * @copyright 2012 Jacques THOORENS
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
 * Projet IRIS-PHP
 */
class GoAd extends _ViewHelper {

    protected static $_Singleton = true;

    public function help() {
        $client = new \Iris\System\Client();
        $lang = $client->getLanguage();
        $title = $this->_('Site powered by Iris-PHP', \TRUE);
        return <<<LINK
<a href="/!iris?LANG=$lang">
    <img src="/!documents/file/resource/logos/IrisSmall.png" alt="Logo IRIS-PHP" title= "$title"/>
</a>
LINK;
    }

}

?>
