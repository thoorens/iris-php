{PHP_TAG}

namespace modules\{MODULE}\controllers;

/**
 * {PROJECTNAME}
 * Created for IRIS-PHP {IRISVERSION}
 * {CONTROLLER_DESCRIPTION}
 * {COMMENT}
 * @author {AUTHOR}
 * @license {LICENSE}
 */
class {MODULECONTROLLER} extends \modules\_application {

    
    /**
     * This method can contain module level
     * settings
     */
    protected final function _moduleInit() {
        // You should modify this demo layout
        $this->_setLayout('!irisShadow');
        // these parameters are only for demonstration purpose
        // and required by the default layout defined in {MODULECONTROLLER}
        $this->__buttons = 1 + 4;
        $this->__logoName = 'mainLogo';
    }

}
