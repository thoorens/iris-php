<?php

namespace Iris\controllers\helpers;
/**
 * replaces all &lt;* tags *> by {magiklinx(....)}
 * to produce special links and tooltip
 *
 * More explanation in \Iris\views\helpers\MagikLinx
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 */
class MagikLinx extends \Iris\controllers\helpers\_ControllerHelper{
    
    public function help($text){
        return preg_replace('/(<\*)(.*?)(\*>)/', '{magikLinx(\'$2\')}', $text);
    }
}

?>
