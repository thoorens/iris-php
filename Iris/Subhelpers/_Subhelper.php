<?php

namespace Iris\Subhelpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A subhelper is a singleton class which realizes all the job for a view helper.
 * The helper returns a link to the subhelper. All the methods of the
 * subhelper are ready for use. The subhelper makes himself the rendering through whatever
 * method it chooses.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Subhelper implements \Iris\Translation\iTranslatable {
    use \Iris\Translation\tSystemTranslatable;
    use \Iris\views\helpers\tViewHelperCaller;
    use \Iris\Engine\tSingleton;
    
    /**
     * A terminator for using fluent methods in {(  )} context
     * (method __toString() renders it unnecessary)
     * @var string
     */
    public $__ = '';

    

    /**
     * Permits to cut the fluent interface in a script context
     *  
     * @return string
     */
    public function __toString() {
        return '';
    }
    
}

