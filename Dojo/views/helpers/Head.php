<?php

namespace Dojo\views\helpers;

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
 * This helper loads all Dojo script files necessary to 
 * run the active parts of the page.
 * All file names have been collected in Dojo\Manager and Dojo\Bubble
 * by the respective dojo helpers. 
 *
 */
class Head extends \Iris\views\helpers\_LoaderHelper{

    
    public function render(){
        return $this->help();
    }
    
    public function help() {
        $manager = \Dojo\Manager::GetInstance();
        if(!$manager->isActive()){
            return '';
        }
        $source = $manager->getURL();
        $script = $manager->getScript();
        $style = $manager->getStyle();
        $parseOnLoad = $manager->getParseOnLoad();
        $debug = $manager->getDebug();

        // Loads css and js scripts
        $text = '';
        foreach($manager->getStyleFiles() as $file=>$dummy){
            $text .= sprintf('<link rel="stylesheet" type="text/css" href="%s">'."\n",$file);
        }
        $text .= <<< BASE
<link rel="stylesheet" type="text/css" href="$source/dijit/themes/$style/$style.css">
<script>
    dojoConfig = {parseOnLoad: $parseOnLoad, debug:$debug}
</script>
<script type="text/javascript" src="$script">
</script>
BASE;

        // loads necessary scripts for dojo functions
        $text .= "<script type=\"text/javascript\">\n";
//        foreach ($manager->getRequisites() as $key => $requisite) {
//            echo "Problem with <b>$key</b><br>";
//            $requisite = '"dojo/parser",'.$requisite;
//            $initCode = '';
//            $text .= "require([$requisite]$initCode);\n";
//        }
        /* @var $bubble \Dojo\Engine\Bubble */
        foreach(\Dojo\Engine\Bubble::GetAllBubbles() as $bubble){
            $text .= $bubble->render();
        }
        $text .= "</script>\n";
        return $text;
    }

}
?>
