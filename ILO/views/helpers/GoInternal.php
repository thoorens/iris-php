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
 * @version $Id: $
/**
 * This helper creates administration and demo buttons
 * 
 */
class GoInternal extends _ViewHelper {

    const ADMIN = 1;
    const MAIN = 2;
    const ROOT = 4;
    const WORKBENCH = 8;
    
    protected static $_Singleton = true;

    public function help($command,$developmentOnly=FALSE) {
        $developmentOnly = FALSE;
        if(is_numeric($command)){
            return $this->_variousButtons($command,$developmentOnly);
        }
        switch ($command) {
            case 'admin':
                $text = 'Administration';
                $uri = '/!'.$command;
                $comment = $this->_('Go to administration tools...',TRUE);
                $developmentOnly =TRUE;
                break;
            case 'reset':
                $text = 'RESET';
                $uri = '/!iris/reset';
                $comment = $this->_('Reset the session',TRUE);
                break;
            case 'main':
                $text = $this->_('Return to main page',TRUE);
                $uri = '/';
                $comment = $this->_('Quit admin module and return to the site welcome page',TRUE);
                break;
            case 'irisroot':
                $text = $this->_('Iris PHP official web site',TRUE);
                $uri = 'http://irisphp.org';
                $comment = $this->_('Return to irisphp.org',TRUE);
                break;
            case 'iriswb':
                $text = 'Iris Work Bench';
                $uri = 'start';
                $comment = $this->_('Start Iris Work Bench',TRUE);
                break;
        }
        if($developmentOnly and \Iris\Engine\Program::IsProduction()){
            return '';
        }
        return $this->_view->button($text,$uri,$comment);
    }

    private function _variousButtons($numbers,$developmentOnly){
        if($developmentOnly and \Iris\Engine\Program::IsProduction()){
            return '';
        }
        $text = '';
        if($numbers & self::ADMIN){
            $text .= $this->help('admin');
        }
        if($numbers & self::MAIN){
            $text .= $this->help('main');
        }
        if($numbers & self::ROOT){
            $text .= $this->help('irisroot');
        }
        if($numbers & self::WORKBENCH){
            $text .= $this->help('iriswb');
        }
        return $text;
    }
}

?>
