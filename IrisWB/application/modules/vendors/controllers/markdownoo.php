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
class markdownoo extends _vendors {

    
    public function indexAction($test=1) {
        $text = $this->textGenerator($test);
        $this->setViewScriptName('/all');
        $md = new \Iris\Vendors\MarkdownOO\MarkdownOO($text);
        $this->__md = $md;
    }

}
