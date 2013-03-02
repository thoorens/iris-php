<?php

namespace Iris\views\helpers;

/*
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

/*
 * This file is part of IRIS-PHP.
 */

/**
 * An easy way load css files or script fragment. Each fragment/file receives
 * a index name so it won't loaded twice.
 * Two way to use it:<ul>
 * <li> ->styleLoader('/css/mystyle.css');
 * <li> ->styleLoader('test',"#test {background-color:white}");
 * </ul>
 */
class StyleLoader extends _LoaderHelper {

    private $_styles = array();
    private $_styleFiles = array();

    /**
     * Add a new style or a new style file
     * 
     * @param string $name name of the style or of the file
     * @param string $content content of the style (NULL in case of file)
     */
    public function help($name = NULL, $content = NULL) {
        if (is_null($content) or is_numeric($content)) {
            // using name as index avoid duplicates
            $this->_styleFiles[$name] = '';
        }
        else {
            $this->_styles[$name] = $content;
        }
        if(is_numeric($content)){
            return $this;
        }
    }

    public function load($name, $content = NULL){
        return $this->help($name, $content);
    }
    
    /**
     * Render styles and links to style file
     * 
     * @return string 
     */
    public function render($ajaxMode) {
        if ($ajaxMode) {
            return '';
        }
        // render styles
        $text = '';
        foreach ($this->_styles as $style) {
            $text .= <<<ENDSTYLE
<style>\n
        $style    
</style>\n
ENDSTYLE;
        }
        // render style files
        foreach ($this->_styleFiles as $file => $dummy) {
            $url = $this->_URL($file);
            $text .= '<link  test="" href="' . $url . '" rel="stylesheet" type="text/css" />';
            $text .= "\n";
        }
        return $text;
    }

    private function _URL($file) {
        if ($file[1] == '!') {
            return $file;
        }
        return "/css/$file";
    }

}

?>
