{PHP_TAG}

namespace modules\{MODULE}\controllers;

/**
 * A controller with CRUD capabilities generated by 
 * iris.php -- entitygenerate
 * 
 */
class {CONTROLLER} extends _{MODULE} {
use \Iris\DB\DataBrowser\tCrudManager;
    
    public function _init() {
        // A proposal for the title
        $this->__Title = "{ENTITY} manager"; 
    }
    
    public function indexAction() {
        $icons = \Iris\Subhelpers\Crud::getInstance();
        $icons
                // controller serving as a manager
                ->setController('/{MODULE}/{CONTROLLER}')
                // action name end (for instance insert_XXXX)
                ->setActionName('{ENTITY}')
                // gender and entity name for tooltip (example M'_employee: masculine and special article 'an')
                ->setEntity("{TOOLTIP}")
                // field containing a significative description 
                ->setDescField('{=DESCRIPTION=}')
                // primary key field
                ->setIdField({ID});
        // Get all the data in {ENTITY}
        $tEntity = \models\T{ENTITY}::GetEntity();
        $this->__lines = $tEntity->fetchAll();
    }

}
