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
class markdowng extends _vendors {

    
    public function indexAction($test=1) {
        $this->setViewScriptName('/all');
        $text = $this->textGenerator($test);
        $parser = new \Vendors\MarkDownG\Markdown_Parser();
        $md = $parser->transform($text);
        $this->__md = $md;
    }

}
