<?php


namespace iris\views\helpers;

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
 * Returns an html text consisting of a localized title "Page under construction"
 * and a logo representing works in progres
 *
 */ 
class UnderConstruction extends \Iris\views\helpers\_ViewHelper{

    
    public function help(){
        $html = "<h3>".$this->_('Page under construction',TRUE)."</h3>\n";
        $html .= $this->image('construction.png',$this->_('Page under construction',TRUE),NULL,'/!documents/file/resource/images');
        return $html;
    }
    

}

?>
