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
        $gibberish = $this->callViewHelper('loremIpsum',[0, 51, 53, 55]);
        $this->__text = <<<END
   <h3>Test of quoted text</h3>
        <p>$gibberish
        </p>
        <?=$placeOfContent?>
        <p><p>{loremIpsum([52, 54, 56])}</p>
        {another}
END;
    }

    public function simpleQuoteAction() {
        $this->__content = "<h4>This title has been defined elsewhere</h4>";
        $this->__another = "<h5>Dummy subtitle</h5>";
        $gibberish = $this->callViewHelper('loremIpsum',[0, 61, 63, 65]);
        $explanation = $this->callViewHelper('wbHeader',$this->_view);
        $color = $this->callViewHelper('wbColors','GRAY1');
        $text = <<<END
    $explanation
    <div id="content" $color>            
        <div class="quoted2">
            <h3>Test of quoted text</h3>
            <p>$gibberish</p>
            {content}
            <p>{loremIpsum([52, 54, 56])}</p>
            {another}
        </div>
    </div>
END;
        $this->quote($text);
    }

}
