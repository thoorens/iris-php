<?php

namespace Iris\views\helpers;


   /**
    * Modified class "Iris\views\helpers\Demo"
    * Add your own code...    
    */
    class Demo extends core_Demo {
    
        public function help($text) {
            return parent::help(strtoupper($text));
        }
        
    }