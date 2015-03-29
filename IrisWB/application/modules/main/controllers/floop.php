<?php

namespace modules\main\controllers {

    /**
     * 
     * Created for IRIS-PHP 0.9 - beta
     * Test of Loops
     * 
     * @author jacques
     * @license not defined
     */
    class floop extends _main {

        /**
         * 
         */
        public function indexAction() {
            $this->__numbers = [1, "two", "trois", "cuatro", "fünf"];
        }

        /**
         * 
         */
        public function assocAction() {
            $this->__numberTranslations = [
                'un' => 'uno',
                'deux' => 'dos',
                'trois' => 'tres',
                'quatre' => 'cuatro',
                'cinq' => 'cinco',
            ];
        }

        /**
         * 
         */
        public function objectAction() {
            // init a special object with 3 properties
            $demo = new \wbClasses\Demo();
            $demo->setColor('blue')
                    ->setPrice(1000)
                    ->setLength(120);
            $this->__demo = $demo;
            $function = function($item = \NULL, $key = \ NULL) {
                $context = " 
          <div class=\"oldscreen old2\">
             $key - $item
          </div>";
                return $context;
            };
            $this->callViewHelper('floop')->setFunction('mytest', $function);
        }

        /**
         * 
         */
        public function globalAction() {
            // init a special object with 3 properties
            $demo = new \wbClasses\Demo();
            $demo->setColor('blue')
                    ->setPrice(1000)
                    ->setLength(120);
            $this->__demo = $demo;
            $this->callViewHelper('floop')->setFunction('mytest', 'test');
        }

        /**
         * A static method to create the view context for the different properties of the object
         * A non static method will be equivalent
         * 
         * @param string $item
         * @param string $key
         * @return string
         */
        public static function Test($item = \NULL, $key = \ NULL) {
            $context = " 
          <div class=\"oldscreen old4\">
             $key - $item
          </div>";
            return $context;
        }

        /**
         * 
         */
        public function userAction() {
            // init a special object with 3 properties
            $demo = new \wbClasses\Demo();
            $demo->setColor('blue')
                    ->setPrice(1000)
                    ->setLength(120);
            $this->__demo = $demo;
            $this->callViewHelper('floop')->setFunction('mytest', [$this, 'Test']);
        }

    }

}

namespace {

    /**
     * A global function to create the view context for the different properties of the object
     * @param string $item
     * @param string $key
     * @return string
     */
    function test($item = \NULL, $key = \ NULL) {
        $context = " 
          <div class=\"oldscreen old3\">
             $key - $item
          </div>";
        return $context;
    }

}