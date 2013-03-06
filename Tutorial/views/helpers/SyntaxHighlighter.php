<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tutorial\views\helpers;

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
 * An interface to the excellent Alex Gorbatchev's Syntax Highlighter
 * @see http://alexgorbatchev.com/SyntaxHighlighter
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class SyntaxHighlighter extends \Iris\views\helpers\_ViewHelper{

    protected static $_Singleton = TRUE;

    public function help($languages = \NULL, $theme = 'ThemeDefault'){
        static $initialized = \FALSE;
        if(!$initialized){
            $initialized = \TRUE;
            $this->_requisite($languages, $theme);
        }
        return $this;
    }

    public function run(){
        
    }
    
    private function _requisite($languages, $theme) {
        if(is_null($languages)){
            $languages = ['Php'];
        }
        $this->callViewHelper('javascriptLoader','core','/js/sh_scripts/shCore.js');
        foreach($languages as $language){
            $this->_view->javascriptLoader($language,sprintf("/js/sh_scripts/shBrush%s.js",$language));
        }
        $this->callViewHelper('styleLoader',"sh_styles/shCore.css");
        $this->callViewHelper('styleLoader',sprintf("sh_styles/sh%s.css",$theme));
        $this->callViewHelper('javascriptLoader','start_sh','SyntaxHighlighter.all()');
    }
    
    
}

/*

<!--Include required JS files-->
<script type = "text/javascript" src = "js/shCore.js"></script>

<!--
    At least one brush, here we choose JS. You need to include a brush for every
    language you want to highlight
-->
<script type="text/javascript" src="css/shBrushJScript.js"></script>

<!-- Include *at least* the core style and default theme -->
<link href="css/shCore.css" rel="stylesheet" type="text/css" />
<link href="css/shThemeDefault.css" rel="stylesheet" type="text/css" />

<!-- You also need to add some content to highlight, but that is covered elsewhere. -->
<pre class="brush: js">
function foo()
{
}
</pre>

<!-- Finally, to actually run the highlighter, you need to include this JS on your page -->
<script type="text/javascript">
    SyntaxHighlighter.all()
</script>*/
