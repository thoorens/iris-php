<?php



namespace Iris\Forms\Elements;

use Iris\Forms as ifo;
use Iris\Forms\Validators as iv;

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
 */

/**
 * An element of type TextArea in a form
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class AreaElement extends \Iris\Forms\_Element {

    /**
     * In that case, tag has structure <tag ...> Value </tag>
     * @var boolean 
     */
    protected static $_EndTag = TRUE;

    public function __construct($name,$options = array()) {
        parent::__construct($name, 'textarea', $options);
        $this->_subtype = '';
    }
 
    
}


