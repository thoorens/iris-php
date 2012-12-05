<?php

namespace modules\manager\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of md5
 * 
 * @author jacques
 * @license not defined
 */
class md5 extends _manager {

    public function saveAction($module, $controller, $action, $md5) {
        $url = "/$module/$controller/$action";
        $tSequences = new \models\TSequence();
        $screen = $tSequences->fetchRow('URL=', $url);
        $screen->Md5 = $md5;
        $screen->save();
        $this->reroute($url);
    }

}
