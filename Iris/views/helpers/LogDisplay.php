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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */

/**
 * This helper displays all debugging messages that have been
 * recorded during page generation. It has no effect in 
 * production environement. It should be placed  at the end
 * of the layout (it uses the CSS 'log' clas predefined in ILO
 * collection).
 * 
 */
class LogDisplay extends _ViewHelper {

    
    public function help() {
        if (\Iris\Engine\Mode::IsDevelopment()) {
            $logMessages = \Iris\Log::GetInstance()->render();
            if ($logMessages != '') {
                return "<div class=\"log\">\n$logMessages\n</div>";
            }
        }
    }

}


