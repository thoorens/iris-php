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
 * This subhelper will create a html table
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Table extends _FlexibleSubhelper {

    const COLUMN = 'C';
    const RAW = 'R';

    
    /**
     * Specifies the order of the parameters in the array parameter of
     * the constructor
     * 
     * @var string[] 
     */
    protected static $_ParameterList = ['name', 'colums', 'lines', 'class', 'id'];
    
    /**
     * The values of the body part of the table (2 dimension array)
     * The cells covered by column and raw span are ignored
     *  
     * @var mixed[]
     */
    protected $_bodyCells = [];

    /**
     * The position and nature of the span (3 dimension array)
     * @var int[]
     */
    protected $_bodySpans = [];

    /**
     * The values of the head part of the table (2 dimension array)
     * The cells covered by column and raw span are ignored
     * 
     * @var mixed 
     */
    protected $_headCells = [];

    /**
     * The position and nature of the span (3 dimension array)
     * 
     * @var type 
     */
    protected $_headSpans = [];

    /**
     * Fills the content from a two dimension array
     * 
     * @param mixed[] $array
     * @return \Iris\Subhelpers\Table for fluent interface
     */
    public function setContent($array) {
        $this->_bodyCells = $array;
        return $this;
    }

    /**
     * Fills the head part of the table with a two dimension array
     * 
     * @param string[] $array
     * @return \Iris\Subhelpers\Table for fluent interface
     */
    public function setHeadCells($array) {
        $this->_headCells = $array;
        return $this;
    }

    /**
     * Adds a cell in the body part of the tablegiving, with optional span for column and raw
     * 
     * @param mixed $content
     * @param type $raw Raw number of the cell
     * @param type $column Column number of the cell
     * @param type $columnSpan
     * @param type $rawSpan
     * @return \Iris\Subhelpers\Table for fluent interface
     */
    public function setCell($content, $raw, $column, $columnSpan = 1, $rawSpan = 1) {
        $this->_bodyCells[$raw][$column];
        if ($columnSpan > 1) {
            $this->_bodySpans[$raw][$column][self::COLUMN] = $columnSpan;
        }
        if ($rawSpan > 1) {
            $this->_bodySpans[$raw][$column][self::RAW] = $rawSpan;
        }
        $this->_bodyCells[$raw][$column] = $content;
        return $this;
    }

    /**
     * Adds a cell in the head part of the table, with optional span for column and raw
     * 
     * @param mixed $content
     * @param int $raw Raw number of the cell
     * @param int $column Column number of the cell
     * @param type $columnSpan
     * @param type $lineSpans
     * @return \Iris\Subhelpers\Table for fluent interface
     */
    public function setHeadCell($content, $raw, $column, $columnSpan = 1, $lineSpans = 1) {
        $this->_headCells[$raw][$column] = $content;
        $this->setHeadSpan(self::COLUMN, $raw, $column, $columnSpan);
        $this->setHeadSpan(self::RAW, $raw, $column, $lineSpans);
        return $this;
    }

    /**
     * Specifies a special span for a cell in the head
     * 
     * @param string $direction Indicates if it is rawspan (R) or colspan (C) 
     * @param int $raw Raw number of the cell
     * @param int $column Column number of the cell
     * @param type $value The numbers of cell in the span
     * @return \Iris\Subhelpers\Table for fluent interface
     */
    public function setHeadSpan($direction, $raw, $column, $value) {
        if ($value > 1) {
            $this->_headSpans[$raw][$column][$direction] = $value;
        }
        return $this;
    }

    /**
     * Specifies a special span for a cell
     * 
     * @param string $direction The direction of the span C(olumn) or R(aw)
     * @param int $raw Raw number of the cell
     * @param int $column Column number of the cell
     * @param int $value The numbers of cell in the span
     * @return \Iris\Subhelpers\Table  for fluent interface
     */
    public function setBodySpan($direction, $raw, $column, $value) {
        if ($value > 1) {
            $this->_bodySpans[$raw][$column][$direction] = $value;
        }
        return $this;
    }

    /**
     * Sets the formated data member, to allow the special : and ! sign
     * in the cell rendering (may be used by CSS)
     * 
     * @param boolean $value 
     * @return \Iris\Subhelpers\Table  for fluent interface
     */
    public function setFormated($value = \TRUE){
        $this->formated = $value;
        return $this;
    }
    
    /**
     * Permits to define the caption of the table
     * 
     * @param string $text The text to be displayed in the caption
     * @return \Iris\Subhelpers\Table  for fluent interface
     */
    public function setCaption($text){
        $this->caption = $text;
        return $this;
    }
    
    /**
     * Sets the head data member, to indicate if it is necessary 
     * to display the thead and tbody tags
     * 
     * @param boolean $value TRUE indicates that thead and tbody will be present
     * @return \Iris\Subhelpers\Table  for fluent interface
     */
    public function setHead($value = \TRUE){
        $this->head = $value;
        return $this;
    }
    /**
     * Renders the complete table
     * 
     * @return string
     */
    public function _render() {
        $class = $this->class === BLANKSTRING ? '' : " class =\"$this->class\" ";
        $id = $this->id === BLANKSTRING ? '' : " id =\"$this->_id\" ";
        $html[] = sprintf("\n<table%s%s>", $class, $id);
        if($this->caption !== BLANKSTRING){
            $html[] = "<caption>$this->caption</caption>";
        }
        $this->_renderHead($html);
        $this->_renderBody($html);
        $html[] = '</table>';
        return implode("\n", $html);
    }

    /**
     * Renders the head part of the table
     * 
     * @param string[] $htmlLines The array with all the html lines (by ref)
     */
    protected function _renderHead(&$htmlLines) {
        $cellTag = BLANKSTRING;
        if ($this->head) {
            $htmlLines[] = "\t<thead>";
            $cellTag = 'th';
        }
        if ($cellTag === BLANKSTRING) {
            $cellTag = $this->cellTag === BLANKSTRING ? 'td' : 'th';
        }
        foreach ($this->_headCells as $lineNumber => $line) {
            $this->_renderLine($htmlLines, $lineNumber, $line, $cellTag, $this->_headSpans);
        }
        if ($this->head) {
            $htmlLines[] = "\t</thead>";
        }
    }

    /**
     * Renders the body part of the table
     * 
     * @param string[] $htmlLines The array with all the html lines (by ref)
     */
    protected function _renderBody(&$htmlLines) {
        if ($this->head) {
            $htmlLines[] = "\t<tbody>";
        }
        foreach ($this->_bodyCells as $lineNumber => $line) {
            $this->_renderLine($htmlLines, $lineNumber, $line, 'td', $this->_bodySpans);
        }
        if ($this->head) {
            $htmlLines[] = "\t</tbody>";
        }
    }

    /**
     * Render a line of head or body part of the table
     *  
     * @param type $htmlLines The array with all the html lines (by ref)
     * @param type $data The array containing one line of head of body data
     * @param type $cellTag The tag used for a cell (th or td)
     */
    protected function _renderLine(&$htmlLines, $lineNumber, $data, $cellTag, $exceptions) {
        $htmlLines[] = "\t\t<tr>";
        //$cellIndex = 0;
        foreach ($data as $cellIndex => $cell) {
            $colspan = $rowspan = '';
            if (isset($exceptions[$lineNumber][$cellIndex][self::COLUMN])) {
                $colspan = ' colspan="' . $exceptions[$lineNumber][$cellIndex][self::COLUMN] . '"';
            }
            if (isset($exceptions[$lineNumber][$cellIndex][self::RAW])) {
                $rowspan = ' rowspan="' . $exceptions[$lineNumber][$cellIndex][self::RAW] . '"';
            }
            $attributes = $rowspan . $colspan;
            if ($this->formated) {
                $htmlLines[] = $this->_renderFormatedCell($cell, $rowspan . $colspan, $cellTag);
            }
            else {
                $htmlLines[] = "\t\t\t<$cellTag$attributes>" . $cell . "</$cellTag>";
            }
            //"\t\t\t<$cellTag$rowspan$colspan>". $cell . "</$cellTag>";
            //$cellIndex++;
        }
        $htmlLines[] = "\t\t</tr>";
    }

    /**
     * Renders a cell using some format<ul>
     * <li> <:my_data:> will use th instead of td in body
     * <li> <!my_data!> will add a class xxxx_emph where xxxx is the table class name
     * </ul>
     * 
     * @param mided $cell The cell content
     * @param string $attributes The initial span attributes
     * @param string $cellTag The tag used to mark the cell (td or th)
     * @return string
     */
    public function _renderFormatedCell($cell, $attributes, $cellTag) {
        if (preg_match('/:.*:/', $cell)) {
            $cellTag = 'th';
            $cell = substr($cell, 1, strlen($cell) - 2);
        }
        if (preg_match('/!.*!/', $cell)) {
            $attributes.= ' class="' . $this->class . '_emph"';
            $cell = substr($cell, 1, strlen($cell) - 2);
        }
        return "\t\t\t<$cellTag$attributes>" . $cell . "</$cellTag>";
    }

    /**
     * Converts the object to its string representation
     * 
     * @return string
     */
    public final function __toString() {
        return $this->_render();
    }

}
?>




