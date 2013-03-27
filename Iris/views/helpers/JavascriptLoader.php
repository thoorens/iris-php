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
 * An easy way load javascrit files or script fragment. Each fragment/file receives
 * a index name so it won't loaded twice.
 * Two way to use it:<ul>
 * <li> ->javascriptLoader('/js/myscript.js');
 * <li> ->javascriptLoader('test',"SyntaxHighlighter.all()");
 * </ul>
 * 
 */
class JavascriptLoader extends _ViewHelper {

    protected static $_Singleton = \TRUE;
    
    private $_scripts = array();
    private $_scriptFiles = array();

    /**
     * Add a new script or a new script file
     * 
     * @param string $index script name 
     * @param string $content content of the script or file name (ends in .js)
     */
    public function help($index = NULL, $content = NULL) {
        if (!is_null($index)) {
            if (is_string($content) and substr($content, -3) == '.js') {
                $this->_scriptFiles[$index] = $content;
            }
            else {
                $this->_scripts[$index] = $content;
            }
        }
        return $this;
    }

    /**
     * Render script file links and individual scripts
     * 
     * @return string 
     */
    public function render($mode) {
        // render script file links
        $files = '';
        foreach ($this->_scriptFiles as $file) {
            $files .= sprintf('<script type="text/javascript" src="%s"></script>', $file);
            $files .= "\n";
        }
        // render indiviual scripts
        $text = '';
        foreach ($this->_scripts as $scriptLabel=>$script) {
            $text .= "/* Javascript code for $scriptLabel */\n";
            $text .= $script . "\n";
        }
        $code = $text=='' ? '' :sprintf("<script type=\"text/javascript\">\n%s</script>\n",$text);
        return $files.$code;
    }

}

?>
