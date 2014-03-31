<?php

namespace Iris\Documents;

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
 */

/**
 * How to send a clean email
 * Based on a tutorial in french:
 * http://fr.openclassrooms.com/informatique/cours/e-mail-envoyer-un-e-mail-en-php
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ *
 */
class Mail {

    /**
     * Some servers have problems with ends of line. Here we detect them 
     * (strange, they are in Redmond USA :-) )
     */
    private function _endOfLine() {
        if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) {
            $endOfLine = "\r\n";
        }
        else {
            $endOfLine = "\n";
        }
        return $endOfLine;
    }

}

