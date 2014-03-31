<?php

namespace modules\system\controllers;

/**
 * 
 * @param string $label
 * @param mixed $var
 */
function test($label, $var) {
    echo "<h3>$label</h3>";
    echo "Affichage:";
    echo $var;
    echo "<br>";
    echo "Vu comme cha&icirc;ne: $var <br/>";
    if (is_null($var)) {
        echo "Est null<br>";
    }
    if (empty($var)) {
        echo "Est vide<br>";
    }
    if (isset($var)) {
        echo "Est 'set'<br>";
    }
    if (is_numeric($var)) {
        echo "Est num&eacute;rique<br/>";
    }
    if (is_string($var)) {
        echo "Est une cha&icirc;ne<br>";
    }
    if (is_bool($var)) {
        echo "Est bool&eacute;en<br/>";
    }
    if (is_scalar($var)) {
        echo "Est scalaire<br/>";
    }
    iris_debug($var, \FALSE);
}

/**
 * srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of various
 * 
 * @author jacques
 * @license not defined
 */
class various extends _system {

    public function basicfunctionsAction($num = 0) {
        $this->__num = $num;
        $examples = [
            ['NULL', \NULL],
            ['Chaine vide', ''],
            ['Nombre 0', 0],
            ['Tableau vide', []],
            ['Valeur vrai', \TRUE],
            //['Division par 0', 7 / 0],
        ];
        ob_start();
        $line = $examples[$num];
        test($line[0], $line[1]);
        $this->__output = ob_get_contents();
        ob_clean();
    }

}
