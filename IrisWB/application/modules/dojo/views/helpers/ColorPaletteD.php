<?php

namespace Iris\views\helpers;

/*
 * This code is just an example of the use of a Dojo resource
 * Declaration version
 */

class ColorPaletteD extends \Iris\views\helpers\_ViewHelper {

    const MODE7_10 = 0;
    const MODE3_4 = 1;

    public function help($mode = self::MODE7_10) {
        if (is_numeric($mode)) {
            $mode = $mode ? '3x4' : '7x10';
        }
        $id = \Dojo\Engine\Bubble::NewObjectName('CP');
        $bubble = \Dojo\Engine\Bubble::GetBubble($id);
        $bubble->addModule('dijit/ColorPalette', 'ColorPalette');
        $code = <<<CODE
<div data-dojo-type="dijit/ColorPalette" 
    data-dojo-props="onChange:function(){alert(this.value);}, palette:'$mode'">
</div>
CODE;
        return $code;
    }

}
