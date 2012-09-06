<?php



namespace Iris;

/**
 * Description of Sequence
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Sequence {

    public function runTest() {
        $seq = new \Iris\Structure\Sequence('/controller', array(
                    '/one' => 'the first|1',
                    '/two' => 'the second|2',
                    '/three' => 'the third|3'
                ));

        $seq->setBeginning('/one');
        $seq->setEnd('/three');
        foreach ($seq as $url3) {
            \Iris\Engine\Debug::Dump($seq->current());
        }
        Iris\Debug::Dump($seq->current());
        echo "----------------------------------<br>";
        $seq->rewind();
        while ($seq->valid()) {
            \Iris\Engine\Debug::Dump($seq->current());
            $seq->next();
        }
        Iris\Debug::Dump($seq->current());
        echo "----------------------------------<br>";
        $seq->goLast();
        while ($seq->retroValid()) {
            \Iris\Engine\Debug::Dump($seq->current());
            $seq->previous();
        }
        Iris\Debug::Dump($seq->current());
        echo "----------------------------------<br>";

        die('oui!');
    }

}

?>
