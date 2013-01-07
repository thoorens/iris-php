<?php

namespace Iris\views\helpers;

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
 * This helper displays anything passed as parameteter as ascii text, 
 * ignoring html tag.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * @todo : verify the utility of this and suppress it in all layouts
 */
class ShowHtml extends _ViewHelper {

    /**
     * 
     * @param string $data The text to display
     * @param boolean $cut If true, each space is replaced by CRLF
     * @param text $class a class name to qualify the embedded &lt;pre>
     * @return string
     */
    public function help($data, $cut = \FALSE, $class = '') {
        $classAttribute = $class == '' ? '' : " class=\"$class\""; 
        $html = "<pre$classAttribute>\n";
        $formattedData = str_replace('<', '&lt', $data);
        if($cut){
            $formattedData =str_replace(' ', "\n", $formattedData);
        }
        $html .= $formattedData;
        $html .= "</pre>";
        return $html;
    }

}

