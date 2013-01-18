<?php

namespace modules;

/**
 * {PROJECTNAME}
 * Created for IRIS-PHP {IRISVERSION}
 * Description of {CONTROLLER}
 * {COMMENT} 
 * @author {AUTHOR}
 * @license {LICENSE}
 */
class _application extends \Iris\MVC\_Controller {

    /**
     * This method can contain application level
     * settings
     */
    protected final function _applicationInit() {
        // these parameters are only for demonstration purpose
        // and required by the default layout defined in {MODULECONTROLLER}
        $this->__buttons = 1 + 4;
        $this->__logoName = 'mainLogo';
    }

}
