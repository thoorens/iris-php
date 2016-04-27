<?php
namespace IrisInternal\admin\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class sitemap extends _admin {
    
    public function preDispatch() {
        //$this->_setLayout('iris_shadow');
        $client = new \Iris\System\Client();
        $language = $client->getLanguage();
        $this->__language = $language;
    }
    
    public function indexAction(){
        
    }
    
    public function createAction(){
        iris_debug(\Iris\Engine\Memory::Get('param_menu'));
    }

}


