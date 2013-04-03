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
        $this->__bodyColor = 'ORANGE2';
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
        $this->__content = "<h4>This title has been defined elsewhere</h4>";
        $this->__another = "<h5>Dummy subtitle</h5>";
        // Place of content is necessary to avoid the interpretation 
        // of $content in the string. This is only an example, the text
        // could come from a database in real life.
        $placeOfContent = '$content';
        $gibberish1 = $this->callViewHelper('loremIpsum',[0, 51, 53, 55]);
        $gibberish2 = $this->callViewHelper('loremIpsum',[52, 54, 56]);
        $this->__text = <<<END
   <h3>Test of quoted text</h3>
        <p>$gibberish1
        </p>
        <?=$placeOfContent?>
        <p>$gibberish2
        </p>
        {another}
END;
    }

    public function simpleQuoteAction() {
        $this->__content = "<h4>This title has been defined elsewhere</h4>";
        $this->__another = "<h5>Dummy subtitle</h5>";
        // Place of content is necessary to avoid the interpretation 
        // of $content in the string. This is only an example, the text
        // could come from a database in real life.
        $placeOfContent = '$content';
        $gibberish1 = $this->callViewHelper('loremIpsum',[0, 61, 63, 65]);
        $gibberish2 = $this->callViewHelper('loremIpsum',[62, 64, 66]);
        $explanation = $this->callViewHelper('wbExplain',$this->_view);
        $text = <<<END
   <div>
    <div id="page">
    $explanation
    <div class="quoted2">
        <h3>Test of quoted text</h3>
        <p>$gibberish1
        </p>
        <?=$placeOfContent?>
        <p>$gibberish2
        </p>
        {another}
    </div>
    </div>
</div>
   
END;
        $this->quote($text);
    }

}
