<?php

namespace modules\vendors\controllers;

/**
 * Project : srv_www_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of index
 * 
 * @author 
 * @license 
 */
class extended extends _vendors {

    
    public function indexAction($test=1) {
        $text = $this->textGenerator($test);
        $this->setViewScriptName('/all');
        $parser = new \Vendors\Markdown\MarkdownExtra();
        $md = $parser->transform($text);
        $this->__md = $md;
    }

}
