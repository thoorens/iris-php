<?php



namespace IrisTest\Parsers;

/**
 * Test project
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ConfigIterator implements \Iris\Design\iTester {

    public static function Help() {
        
    }

    public function runTest($parameters=NULL) {
        $config = new \Iris\SysConfig\Config('Test');
        $config->prop1 = 1;
        $config->prop2 = 2;
        $iterator = $config->getIterator();
        $solution = array('prop1','prop2');
        \Iris\Engine\Debug::Dump($iterator);
    }

    public static function TestHelp() {
        echo "No parameter needed for ".get_class();
    }

}

?>
