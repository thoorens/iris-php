<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * An easy way load css files or script fragment. Each fragment/file receives
 * a index name so it won't loaded twice.
 * Two way to use it:<ul>
 * <li> ->styleLoader('mystyle.css');
 * <li> ->styleLoader('test',"#test {background-color:white}");
 * </ul>
 */
class StyleLoader extends _Loader {
    
    protected $_extension = 'css';

    /**
     * Renders styles and links to style file (only in non Ajax mode
     * 
     * @param boolean $ajaxMode in Ajax mod, no rendering
     * @return string 
     */
    public function render($ajaxMode) {
        if ($ajaxMode) {
            return '';
        }
        // render styles
        $text = '';
        foreach ($this->_text as $style) {
            $text .= <<<ENDSTYLE
<style>\n
        $style    
</style>\n
ENDSTYLE;
        }
        // render style files
        foreach ($this->_files as $file) {
            $url = $this->_URL($file);
            $text .= '<link  href="' . $url . '" rel="stylesheet" type="text/css" />';
            $text .= "\n";
        }
        return $text;
    }

    public function update($mode, &$text) {
        
    }

}
