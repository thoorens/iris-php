<?php



namespace Iris\Forms\Elements;

use Iris\Forms as ifo;
use Iris\Forms\_FormLayout;

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
 * A field set in a form, grouping various element
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class FieldSet extends \Iris\Forms\_Group {

    protected $_legend = '';
    protected $_groupTag = 'fieldset';

    public function getLegend() {
        return $this->_legend;
    }

    public function setLegend($legend) {
        $this->_legend = $legend;
    }

    public function _specificRender(&$html) {
        if ($this->_legend != '') {
            $html[] = "<legend>$this->_legend</legend>";
        }
    }

}

?>
