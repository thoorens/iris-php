<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Some date display formats in a table
 * 
 */
class TableDate extends _ViewHelper {

    public function help($date, $formats) {

        $text = '    <table class="show">';
        $fullDate = $date->toString('l j F Y');
        $text .= '        <tr><th colspan="3">' . $fullDate . '</th></tr>';
        foreach ($formats as $format => $description) {
            //iris_debug($description);
            $desc = $description[0];
            $formDate = $date->toString($format);
            if ($description[0]==\TRUE) {
                $text .= '            <tr class="specialiris">';
            }
            else {
                $text .= '            <tr>';
            }
            $text .= "                <td><b>&nbsp;$format&nbsp;</b></td>";
            $text .= "                <td>$formDate</td>";
            $text .= "                <td>$desc</td>";
            $text .= "            </tr>";
        }
        $text .= '    </table>';
        return $text;
    }

}
