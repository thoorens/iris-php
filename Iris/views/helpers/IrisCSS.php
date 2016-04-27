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
 * This helper loads css styles used by Log and 
 * internal (intended to be put in <head> )
 * 
 */
class IrisCSS extends _ViewHelper {
    
    

    
    public function help($force=FALSE) {
        $log = \Iris\Engine\Log::GetInstance();
        // in development mode, a simplified CSS to manage PAGE logs
        if (\Iris\Engine\Mode::IsDevelopment() and $log->getPosition()==\Iris\Engine\Log::POS_PAGE){
            return <<<HTML
<link href="/iris_aspect/css/iris_debug.css" rel="stylesheet" type="text/css" />
HTML;
        }
        // some layouts may force a complete loading of iris styles
        elseif($force){
            return <<<HTML
<link href="/iris_aspect/css/iris.css" rel="stylesheet" type="text/css" />
<link href="/iris_aspect/css/irisColor.css" rel="stylesheet" type="text/css" />
HTML;
        }
    }

}


