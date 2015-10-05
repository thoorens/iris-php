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
class markdown extends _vendors {

    
    

    public function indexAction() {
        $text = <<<TEXT
#Title 1

Here is my list:

  * an item
  * another

Title 2
-------
 1. item 1
 2. item 2
 2. item 3                

TEXT;
        $parser = new \Vendors\Markdown\Markdown();
        $md = $parser->transform($text);
        $this->__md = $md;
    }
}
