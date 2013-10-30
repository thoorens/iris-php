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
 * @version $Id: $ */

/**
 * The error toolbar offers a series of buttons to 
 * manage the error display
 */
class ErrorToolbar extends \Iris\views\helpers\_ViewHelper {

    static $_singleton = FALSE;

    public function help() {
        $html = '';
        if (!\Iris\Errors\Handler::IsProduction()) {
            /* @var $settings \Iris\Errors\Settings */
            $settings = \Iris\Errors\Settings::GetInstance();
            $html = sprintf("Flags : %b &diams; ", $settings->getErrorflags());
            /* @var $exception \Iris\Exceptions\_Exception */
            $exception = \Iris\Engine\Memory::Get('untreatedException');
            $router = \Iris\Engine\Router::GetInstance();
            $previous = $router->getPrevious();
            // in case of spontaneous error (e.g. privilege)
            if (is_null($previous)) {
                $oldURL = $router->getAnalyzedURI(\TRUE);
                $trace = \FALSE;
            }
            else {
                $oldURL = $previous->getAnalyzedURI(\TRUE);
                $trace = $exception->getTrace();
                $html .= $this->callViewHelper('button', 'REDO', "/$oldURL", "Repeat error in normal mode");
                $html .= $this->callViewHelper('button', 'HANG', "/$oldURL?ERROR=HANG", "Program will stop at first error.");
                $html .= $this->callViewHelper('button', 'NO WIPE', "/$oldURL?ERROR=KEEP", "All echoed text will be kept.");
                $html .= $this->callViewHelper('button', 'PRODSIM', "/$oldURL?ERROR=PRODSIM", "Simulate error in production mode.");
                $html .= ' &diams; Stack level:';
                for ($level = 0; $level < count($trace); $level++) {
                    $html .= $this->callViewHelper('button', "$level", "/$oldURL?ERRORSTACK=$level", "Display stack information from level " . $level);
                }
                $html .= $this->callViewHelper('button', "ALL", "/$oldURL?ERRORSTACK=-1", "Display stack information in tabs (depends on Dojo)");
                $html .= " &diams; ";
            }
        }
        return $html;
    }

}

