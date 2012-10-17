<?php

namespace modules\main\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Test of the views
 * 
 * @author jacques
 * @license not defined
 */
class views extends _main {

    protected function _init() {
        $this->_setLayout('color');
        $this->__bodyColor = 'BLUE1';
    }

    public function indexAction() {
        $this->__required = '--- implicit ---';
    }

    public function explicitAction() {
        $scriptName = 'EXPLICIT';
        $this->setViewScriptName($scriptName);
        $this->__required = $scriptName;
        $this->__reqPartial = 'pmain';
    }

    public function commonAction() {
        $scriptPath = 'commons/third';
        $this->setViewScriptName($scriptPath);
        $this->__required = $scriptPath;
        $this->__reqPartial = 'commons/pmain';
    }

    public function explicitQuoteAction() {
        $this->__content = "<h2>This title has been defined elsewhere</h2>";
        $this->__another = "<h3>Dummy subtitle</h3>";
        // Place of content is necessary to avoid the interpretation 
        // of $content in the string. This is only an example, the text
        // could come from a database in real life.
        $placeOfContent = '$content';
        $gibberish1 = $this->_view->loremIpsum(6, \FALSE);
        $gibberish2 = $this->_view->loremIpsum(6, \FALSE);
        $this->__text = <<<END
   <h1>Test of quoted text</h1>
        <p>$gibberish1
        </p>
        <?=$placeOfContent?>
        <p>$gibberish2
        </p>
        {another}
END;
    }

    public function simpleQuoteAction() {
        $this->__content = "This title has been defined elsewhere";
        $this->__another = "Dummy subtitle";
        // Place of content is necessary to avoid the interpretation 
        // of $content in the string. This is only an example, the text
        // could come from a database in real life.
        $placeOfContent = '$content';
        $gibberish1 = $this->_view->loremIpsum(6, \FALSE);
        $gibberish2 = $this->_view->loremIpsum(6, \FALSE);
        $text = <<<END
   <h1>Test of quoted text</h1>
        <p>$gibberish1</p>
        <h2><?=$placeOfContent?></h2>
        <p>$gibberish2</p>
        <h3>{another}</h3>
END;
        $this->quote($text);
    }

}
