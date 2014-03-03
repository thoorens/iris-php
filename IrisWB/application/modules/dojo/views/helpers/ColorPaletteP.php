<?php

namespace Iris\views\helpers;

/*
 * This code is just an example of the use of a Dojo resource
 * Programatic version
 */

class ColorPaletteP extends \Iris\views\helpers\_ViewHelper {

    const MODE7_10 = 0;
    const MODE3_4 = 1;

    public function help($mode = self::MODE7_10) {
        if (is_numeric($mode)) {
            $mode = $mode ? '3x4' : '7x10';
        }
        $id = \Dojo\Engine\Bubble::NewObjectName('CP');
        $bubble = \Dojo\Engine\Bubble::GetBubble($id);
        $bubble->addModule('dijit/ColorPalette','ColorPalette');
        $bubble->defFunction(<<<CODE
                
   var myPalette = new ColorPalette({
        palette: "$mode",
        onChange: function(val){ alert(val); }
    }, "$id");       
CODE
        );
        $code = "<span id=\"$id\">this will be replaced</span>";
        return $code;
    }
    
}
