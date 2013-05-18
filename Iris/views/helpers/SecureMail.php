<?php


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
 * @version $Id: $ * 
 */
namespace Iris\views\helpers;

/**
 * This helper returns "mailto" link in which all characters are
 * replaced by random representation : letters, decimal and
 * hexadecimal numbers. The description is in clear unless it
 * is the same as the email address. The intention is prevent
 * robots from using email address to send spam.
 * 
 * CAUTION : this routine is not utf8-safe.
 * @todo: rewrite an UTF8 version of this helper to treat new non ascii domains.
*/
class SecureMail extends \Iris\views\helpers\_ViewHelper{
    
    public function help($address,$description=NULL){
        if(is_null($description)){
            $description=$this->_obfuscate($address);
        }
        return '<a href="'.$this->_obfuscate('mailto:').$this->_obfuscate($address).'">'.$description.'</a>';
    }

    private function ob($mail){
        return strtoupper($mail);
    }
    
    /**
     * Generates an obfuscated version of an email address.
     *
     * @author     Kohana Team
     * @copyright  (c) 2007-2008 Kohana Team
     * @license    http://kohanaphp.com/license.html
     * @filesource Kohana 2.2 : /system/helpers/html
     *
     * @param   string  email address
     * @return  string
     */
    private function _obfuscate($email)
    {
        $safe = '';
        foreach (str_split($email) as $letter)
        {
            switch (($letter === '@') ? rand(1, 2) : rand(1, 3))
            {
                // HTML entity code
                case 1:
                    $safe .= '&#'.ord($letter).';';
                    break;
                // Hex character code
                case 2:
                    $safe .= '&#x'.dechex(ord($letter)).';';
                    break;
                // Raw (no) encoding
                case 3:
                    $safe .= $letter;
                    break;
            }
        }

        return $safe;
    }
}

?>
