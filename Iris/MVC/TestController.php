<?php

namespace Iris\MVC;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This type of controller have no ACL in development mode and are ignored in
 * production mode. Their subclasses are excellent tester
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TestController extends _Controller {
    
    /**
     * Verify ACL only in production mode
     */
    protected function _verifyAcl() {
        if(\Iris\Engine\Mode::IsProduction()){
            parent::_verifyAcl();
        }
    }

    public function security() {

        }

}


