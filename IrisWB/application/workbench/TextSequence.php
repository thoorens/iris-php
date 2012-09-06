<?php

namespace workbench;

/*
 * This file is part of IRIS-PHP.
 *
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IRIS-PHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IRIS-PHP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright 2012 Jacques THOORENS
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * Manage a sequence of tests (this class does not respect MVC paradigm
 * by mixing control and view elements)
 * 
 * @deprecated : Iris Workbench now uses DBSequence as a test list repository
 */
class TextSequence extends \Iris\Structure\ArraySequence implements \Iris\Design\iSingleton {

    private $_mySequence = array(
        '/main/index/index' => 'Iris-PHP Work bench main page|Begin',
        'Levels of layouts' => array(
// layout position
            '/testLayout/layout/applayout' => 'Test an application level layout',
            '/main/layout/module' => 'Test a module level layout',
            '/main/index/controller' => 'Test a controller level layout',
            '/main/layout/action' => 'Test an action level layout',
            // layout components
            '/main/layout/basic' => 'Basic layout with subcontrollers and islets',
            '/testLayout/layout/index' => 'Basic layout in a module'
        ),
        'Naming of views, partials and loops' => array(
            '/main/views/index' => 'Implicit access to a view script',
            '/main/views/explicit' => 'Explicit access to a view script',
            '/main/views/common' => 'Access to a subfolder',
            '/other/views/index' => 'Implicit access to a view script in a module',
            '/other/views/explicit' => 'Explicit access to a view script in a module',
            '/other/views/common' => 'Access to a subfolder in a module',
            '/other/views/inheritedImplicit' => 'Implicit view script inherited from main in a modume',
            '/other/views/inherited' => 'View script inherited from main in a module',
            '/other/views/commonInherited' => 'View script inherited from a main subfolder in a module',
            '/main/loop/simple' => 'Simple loop',
            '/main/loop/recursive' => 'Recursive loop'
        ),
        'Error reporting' => array(
            '/main/stupid/index' => 'Test a non existent module or controller',
            '/main/index/stupid' => 'Test a non existent action',
            '/errors/scripts/layout' => 'Test a non existent view layout',
            '/errors/scripts/view' => 'Test a non existent view script',
            '/errors/scripts/partial' => 'Test a non existent partial script',
            '/errors/variables/view' => 'Test a non existent variable in a view',
            '/errors/variables/partial' => 'Test a non existent variable in a partial',
            '/errors/variables/islet' => 'Test a non existent variable in an islet',
            '/errors/variables/partialPrivate' => 'No access to view from partial'
        ),
        '/main/index/end' => 'End of Iris Work Bench'
    );

    public function __construct() {
        parent::__construct($this->_mySequence);
        $this->_explanationProvider = 'workbench\\Context';
    }

}

?>
