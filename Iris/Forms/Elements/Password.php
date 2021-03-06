<?php

namespace Iris\Forms\Elements;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * An element of type Password in a form
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Password extends \Iris\Forms\_Element {

    /**
     * In that case, tag has structure <tag ...> Value </tag>
     * @var boolean 
     */
    protected static $_EndTag = TRUE;

    public function __construct($name, $options = array()) {
        parent::__construct($name, 'password', $options);
        $this->_subtype = '';
    }

}
