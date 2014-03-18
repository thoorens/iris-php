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
class {CONTROLLER} extends {MODULECONTROLLER} {

    public function indexAction() {
        // this Title var is required by the default layout defined in {MODULECONTROLLER}
        $this->__Title = $this->callViewHelper('welcome',1);
    }
    
    

}
