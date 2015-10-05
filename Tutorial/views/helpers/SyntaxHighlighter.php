<?php

namespace Tutorial\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
            $this->callViewHelper('javascriptLoader',$language,sprintf("/js/sh_scripts/shBrush%s.js",$language));
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
