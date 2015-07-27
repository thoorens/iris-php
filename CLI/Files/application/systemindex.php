{PHP_TAG}

namespace modules\{MODULE}\controllers;

/*
This file is part of IRIS-PHP, distributed under the General Public License version 3.
A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
More details about the copyright may be found at
<http:/>/irisphp.org/copyright> or <http:/>/www.gnu.org/licenses/>
 
@copyright 2011-2015 Jacques THOORENS
 */

/**
 * {CONTROLLER_DESCRIPTION}
 * 
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * 
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ */
class {CONTROLLER} extends {MODULECONTROLLER} {

    public function indexAction() {
        // this Title var is required by the default layout defined in {MODULECONTROLLER}
        $this->__Title = $this->callViewHelper('welcome',1);
    }
    
    

}
