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
class parsedown extends _vendors {

    
    public function indexAction($test=1) {
        $text = $this->textGenerator($test);
        $this->setViewScriptName('/all');
        $parser = new \Vendors\Parsedown\Parsedown();
        $md = $parser->text($text);
        $this->__md = $md;
    }

}
