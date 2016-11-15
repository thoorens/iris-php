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
 * An easy way load javascrit files or script fragment. Each fragment/file receives
 * a index name so it won't loaded twice.
 * Two way to use it:<ul>
 * <li> ->javascriptLoader('/js/myscript.js');
 * <li> ->javascriptLoader('test',"SyntaxHighlighter.all()");
 * </ul>
 * In case of PHP call, please put two arguments in an array
 */
class JavascriptLoader extends _Loader {

    protected $_extension = 'js';
    
    /**
     * Render script file links and individual scripts
     * 
     * @return string 
     */
    public function render($ajaxMode) {
        if ($ajaxMode) {
            return '';
        }
        // render script file links
        $files = '';
        foreach ($this->_files as $file) {
            $files .= sprintf('<script type="text/javascript" src="%s"></script>', $this->_URL($file));
            $files .= "\n";
        }
        // render indiviual scripts
        $text = '';
        foreach ($this->_text as $scriptLabel => $script) {
            $text .= "/* Javascript code for $scriptLabel */\n";
            $text .= $script . "\n";
        }
        $code = $text == '' ? '' : sprintf("<script type=\"text/javascript\">\n%s</script>\n", $text);
        return $files . $code;
    }

    public function update($mode, &$text) {
        
    }

}
