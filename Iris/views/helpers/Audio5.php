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
 * A helper for sound in html5
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Audio5 extends _ViewHelper {

    const ALL = -1;
    const MP4 = 1;
    const MP3 = 2;
    const OGG = 4;
    
    public function help($fileName = NULL, $id='', $mode = self::ALL, $autoplay = \TRUE, $controls = \TRUE) {
        if(is_null($fileName)){
            return $this;
        }
        else{
            return $this->render($fileName, $id, $mode, $autoplay, $controls);
        }
    }
    
    public function render($fileName, $id, $mode = self::ALL, $autoplay = \TRUE, $controls = \TRUE) {
        $controlsAttribute = $controls ? ' controls ' : '';
        $autoplayAttribute = $autoplay ? ' autoplay ' : '';
        $html = "<audio id=\"$id\" $controlsAttribute $autoplayAttribute >\n";
        if($mode & self::MP4){
            $html .= "\t <source src=\"$fileName.aac\" type=\"audio/mp4\">\n";
        }
        if($mode & self::MP3){
            $html .= "\t <source src=\"$fileName.mp3\" type=\"audio/mp3\">\n";
        }
        if($mode & self::OGG){
            $html .= "\t <source src=\"$fileName.ogg\" type=\"audio/ogg\">\n";
        }
        $html .= "</audio>\n";
        return $html;
    }

}

