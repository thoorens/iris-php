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
 * This trait implements the way to register into Head subhelper to be
 * rendered in the final tuning of the output. Each class will have its 
 * proper rendering method.
 *  
 * Used by <ul>
 *    <li> Iris\views\helpers\JavascriptLoader
 *    <li> Iris\views\helpers\StyleLoader
 *    <li> Dojo\Engine\Head
 * </ul>
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * 
 */
trait tLoaderRegister {

    /**
     * Register will be called by all classes having 
     * a _subclassInit (all _ViewHelper subclasses)
     */
    protected function _subclassInit() {
        $this->register();
    }

    /**
     * A way to add in output the content of the class (in final tuning)
     * 
     */
    protected abstract function render($mode);

    public abstract function update($mode, &$text);

    /**
     * Each class containing this trait must register in Head subhelper
     * This method must be explicitely called if no _subclassInit method is
     * available (e.g. Dojo\Engine\Head)
     */
    public function register() {
        \Iris\Subhelpers\Head::RegisterManager(get_called_class());
    }

}
