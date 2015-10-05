<?php
namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * An easy way to add some starting code in javascript 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ *
 */
class JavascriptStarter extends _ViewHelper implements \Iris\Design\iSingleton {

    protected static $_Singleton = \TRUE;
    private $_runningScripts = [];

    /**
     * Add a new running script
     * 
     * @param string $index script name 
     * @param string $content content of the script or file name (ends in .js)
     */
    public function help($index = NULL, $content = NULL) {
        if (!is_null($index)) {
            $this->_runningScripts[$index] = $content;
        }
        return $this;
    }

    /**
     * Render script file links and individual scripts
     * 
     * @return string 
     */
    public function render() {
        $scriptText = '';
        foreach ($this->_runningScripts as $script) {
            $scriptText .= $script . "\n";
        }
        $text = $scriptText == '' ? '' : sprintf("<script type=\"text/javascript\">\n%s</script>\n", $scriptText);
        return $text;
    }

}


