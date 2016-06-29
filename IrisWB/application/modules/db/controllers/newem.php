<?php

namespace modules\db\controllers;

/**
 * Project : srv_www_trydb
 * Created for IRIS-PHP 1.0 RC2
 * Description of newem
 * 
 * @author 
 * @license 
 */
class newem extends _db {

    protected function _init() {
        $this->setDefaultScriptDir('newem');
    }

    public function indexAction($max) {
        for($i=1;$i<1000;$i++){
            $tab[$i] = 0;
        }
        $tab[0] = 0;
        // this Title var is required by the default layout defined in _db
        $this->__Title = $this->callViewHelper('welcome', 1);
        for ($number = 2; $number < $max; $number++) {
            $n = $number;
            $c = 1;
            while ($n != 1) {
                $c++;
                if (($n % 2) == 0) {
                    $n = $n / 2;
                }
                else {
                    $n = ($n * 3) + 1;
                }
            }
            $tab[$c]++;
                //if($c>100)
                
        }
        for($i=1;$i<1000;$i++){
            if ($tab[$i] > 0) {
                print ($i . " - " . $tab[$i] . "<br/>");
            }
        }
        die('ok');
    }

    public function zeroAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>db - newem - zero</h1>",
            'buttons' => 1 + 4,
            'logoName' => 'mainLogo']);
    }

    public function zero_servAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>db - newem - zero_serv</h1>",
            'buttons' => 1 + 4,
            'logoName' => 'mainLogo']);
    }

    public function oneAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>db - newem - one</h1>",
            'buttons' => 1 + 4,
            'logoName' => 'mainLogo']);
    }

    public function one_servAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>db - newem - one_serv</h1>",
            'buttons' => 1 + 4,
            'logoName' => 'mainLogo']);
    }

    public function internalAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>db - newem - internal</h1>",
            'buttons' => 1 + 4,
            'logoName' => 'mainLogo']);
    }

    public function internal_servAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>db - newem - internal_serv</h1>",
            'buttons' => 1 + 4,
            'logoName' => 'mainLogo']);
    }

    public function minusAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>db - newem - minus</h1>",
            'buttons' => 1 + 4,
            'logoName' => 'mainLogo']);
    }

    public function minus_servAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>db - newem - minus_serv</h1>",
            'buttons' => 1 + 4,
            'logoName' => 'mainLogo']);
    }

}
