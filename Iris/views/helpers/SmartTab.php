<?php

namespace Iris\views\helpers;
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
 */

/**
 * This helper create a serie of table rows (no &lt;table> tag)
 * for displaying data contained in an array.
 * By default, each part of the array is considered as a string.
 * A fonction or a helper may be transmited
 * The number of column is by default 3 (can be changed).
 * One can provide classes for the raw and the cell.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */

defined('CRLF') or define('CRLF', "\n");
defined('TAB') or define('TAB', "\t");

/**
 * Provoque le déplacement du logo quand on y place la souris
 */
class SmartTab extends \Iris\views\helpers\_ViewHelper {

    protected $_singleton = \TRUE;
    
    /**
     * Type of formatting: raw format
     */
    const NOFUNCTION = 1;
    /**
     * Type of formatting: helper
     */
    const HELPER = 2;
    /**
     * Type of formatting: standard function
     */
    const STDFUNCTION = 3;

    /**
     * A dummy array to serve as a test
     * @var string[]
     */
    private $_data = ["data 1", "data 2", "data 3", "data 4", "data 5", "data 6"];
    /**
     *
     * @var string
     */
    private $_formatFunctionName = \NULL;
    private $_type = self::NOFUNCTION;


    /**
     * Returns the instance of the helper
     * 
     * @return \Iris\views\helpers\SmartTab
     */
    public function help() {
        return $this;
    }

    /**
     * Specifies that the data will be formatted for a function 
     * whose name is given 
     * 
     * @param string $_name the name of the helper
     * @return \Iris\views\helpers\SmartTab for fluent interface
     */
    public function setFormatFunctionName($_name) {
        $this->_formatFunctionName = $_name;
        $this->_type = self::STDFUNCTION;
        return $this;
    }

       
    /**
     * Specifies that the data will be formatted for a helper 
     * whose name is given 
     * 
     * @param string $_name Name of the helper
     * @return \Iris\views\helpers\SmartTab for fluent interface
     */
    public function setFormatHelperName($_name) {
        $this->_formatFunctionName = $_name;
        $this->_type = self::HELPER;
        return $this;
    }


    
    /**
     * Realizes the rendering of the array in the tab
     * 
     * @param array $data The data to display in the tab
     * @param int $col Number of columns
     * @param string $trClass An optional class for the rows
     * @param string $tdClass An optional class for the cells
     * @return string the html string corresponding to the inner part of the tab
     */
    public function render($data = [], $col = 3, $trClass = '', $tdClass = '') {
        if (count($data) == 0) {
            $data = $this->_data;
        } 
        $offset = 0;
        $html = '';
        foreach ($data as $item) {
            $trAttribute = $trClass == '' ? '' : "class=\"$trClass\"";
            $tdAttribute = $tdClass == '' ? '' : "class=\"$tdClass\"";
            if ($offset == 0)
                $html .= TAB . "<tr $trAttribute>" . CRLF;
            $html .= TAB . TAB . "<td $tdAttribute>" . CRLF;
            switch ($this->_type) {
                case self::HELPER:
                    $html .= $this->callViewHelper($this->_formatFunctionName, $item);
                    break;
                case self::STDFUNCTION:
                    $function = $this->_formatFunctionName;
                    $html .= $function($item);
                    break;
                case self::NOFUNCTION:
                    $html .= $item;
                    break;
            }
            $html .= TAB . TAB . '</td>' . CRLF;
            $offset = (++$offset) % $col;
            if ($offset == 0)
                $html .= TAB . '</tr>' . CRLF;
        }
        if ($offset != 0)
            $html .= TAB . '</tr>' . CRLF;
        return $html;
    }

}

