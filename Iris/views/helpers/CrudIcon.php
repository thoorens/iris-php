<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * This helper is part of the CRUD facilites offered by Iris. It serves to
 * display icons for the different actions. The most part of its job is
 * done by \Iris\Subhelpers\_CrudIconManager.
 *
 */
class CrudIcon extends _ViewHelper{

    use \Iris\Subhelpers\tSubhelperLink;

    /**
     * Defines the subhelper used by the class. One can use another subhelper
     * in a derived class by defining $_AlternateSubhelper
     */
    protected function _init() {
        $managerClass = \Iris\SysConfig\Settings::$DefaultModelLibrary.'crud\CrudIconManager';
        $this->_subhelperName = $managerClass::GetInstance();
    }



}
