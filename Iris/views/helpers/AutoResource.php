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
 * @version $Id: $ * @todo : verify the utility of this and suppress it in all layouts
 */

/**
 * A way to manage script and style references after all the page
 * has been generated. help() place an html comment and HeaderBodyTuning()
 * replaces it by the necessary style and script loading
 * 
 */
class AutoResource extends \Iris\views\helpers\_ViewHelper {
    const LOADERMARK = "\t<!-- LOADERS -->\n";

    private $_additionalHeadLoader = array();
    protected static $_Singleton = \TRUE;

    public function addLoader($className) {
        $this->_additionalHeadLoader[] = $className;
    }
    

    /**
     * Returns a html comment to be replaced at later stage by scripts and
     * styles
     * 
     * @return string
     */
    public function help() {
        return self::LOADERMARK;
    }

    
    
    /**
     * Replaces the html comment by scripts and styles
     * and add javascript code before &lt;/body>
     * 
     * @param string $text The page text before finalization 
     * @param \Iris\Time\RunTimeDuration $stopWatch
     */
    public static function HeaderBodyTuning(&$text, $stopWatch = \NULL, $componentId = 'iris_RTD') {
        $auto = self::GetInstance();
        $loaders = "\t<!-- LOADERS begin -->\n";
        foreach ($auto->_additionalHeadLoader as $loaderName) {
            $loader = $loaderName::getInstance();
            $loaders .= $loader->render();
        }
        $loaders .= "\t<!-- LOADERS end -->\n";
        $text = str_replace(self::LOADERMARK,$loaders,$text);
        $starter = \Iris\views\helpers\JavascriptStarter::GetInstance()->render();
        $starter .= \Iris\views\helpers\Signature::computeMD5($text);
        if(!is_null($stopWatch)){
            $starter .= $stopWatch->jsDisplay($componentId);
        }
        $text = str_replace('</body>',$starter."\n</body>",$text);
    }

}

