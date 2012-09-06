<?php

namespace modules\{MODULE}\controllers;

/**
 * {PROJECTNAME}
 * Created for IRIS-PHP {IRISVERSION}
 * Description of {CONTROLLER}
 * {COMMENT}
 * @author {AUTHOR}
 * @license {LICENSE}
 */
class {CONTROLLER} extends {MODULECONTROLLER} {

    public function indexAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => {TITLE},
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }

}
