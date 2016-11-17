<?php

namespace Iris\Subhelpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * This subhelper will create an html table cell
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TableElement {

    /**
     * an empty element (in case of rowspan or colspan
     */
    const NOCELL = 'no';

    /**
     * a data cell tag
     */
    const TD = 'td';

    /**
     * a title cell tag
     */
    const TH = 'th';

    /**
     * by default a table element is a data cell
     * @var string
     */
    private $_ElementType = self::TD;

    /**
     * the content of a cell may a cell or a way go to previous cell or line
     * @var mixed
     */
    private $_content;

    /**
     * a rowspan attibute value
     * @var int
     */
    private $_rowspan = 1;

    /**
     * a colspan attribute value 
     * @var int
     */
    private $_colspan = 1;

    /**
     * 
     * @param type $content
     * @return \Iris\Subhelpers\TableElement
     */
    public function __construct($content, $type = self::TD) {
        if ($type !== self::NOCELL) {
            $this->_content = $content;
            $this->_ElementType = $type;
        }
        else {
            $this->_ElementType = self::NOCELL;
        }
        return $this;
    }

    /**
     * a factory method to create a data cell
     * 
     * @param type $content
     * @return type
     */
    public static function CreateTDCell($content) {
        $cell = new TableElement($content);
        return $cell->setElementType(self::TD);
    }

    /**
     * a factory method to create a title cell
     * 
     * @param type $content
     * @return type
     */
    public static function CreateTHCell($content) {
        $cell = new TableElement($content);
        return $cell->setElementType(self::TH);
    }

    /**
     * an accessor set for the ekement type
     * 
     * @param string $ElementType
     * @return \Iris\Subhelpers\TableElement  (for fluent interface)
     */
    public function setElementType($ElementType) {
        $this->_ElementType = $ElementType;
        return $this;
    }

    /**
     * a renderer for the cell
     * 
     * @return string
     */
    public function render() {
        // if cell is empty nothing to display
        if ($this->_ElementType === self::NOCELL) {
            $value = '';
        }
        else {
            $content = explode(Table::GetStyleSeperator(), $this->_content);
            $class = $id = '';
            switch (count($content)) {
                case 1:// no class, no id
                    $text = $content[0];
                    break;
                case 2:// no id
                    $class = $this->_label($content[0], 'class');
                    $text = $content[1];
                    break;
                case 3:// class and id
                    $class = $this->_label($content[0], 'class');
                    $text = $content[1];
                    $id =  $this->_label($content[3], 'id');
                    break;
            }
            $rawSpan = $this->_rowspan > 1 ? ' rowspan="' . $this->_rowspan . '"' : '';
            $colSpan = $this->_colspan > 1 ? ' colspan="' . $this->_colspan . '"' : '';
            $tag1 = \sprintf("\t\t\t<%s%s%s%s%s>", $this->_ElementType, $rawSpan, $colSpan, $class, $id);
            $value = \sprintf('%s%s</%s>', $tag1, $text, $this->_ElementType);
        }
        return $value;
    }

    protected function _label($value, $prefix) {
        if (is_numeric($value)) {
            if ($value == 0) {
                $this->_ElementType = 'th';
                return '';
            }
            else {
                $label = "$prefix" . $value;
            }
        }
        else {
            $label = $value;
        }
        return sprintf(' %s="%s"', $prefix, $label);
    }

    /**
     * 
     * @param type $elementNumber
     * @return TableElement
     */
    public function getElement($elementNumber) {
        return $this->_content[$elementNumber];
    }

    public function getType() {
        return $this->_ElementType;
    }

    /**
     * Increment the rawspan attribute of the cell
     * @param int $increment
     * @return \Iris\Subhelpers\TableElement (for fluent interface)
     */
    public function IncRowspan($increment = 1) {
        $this->_rowspan += $increment;
        return $this;
    }

    /**
     * Increments the colspan attribute of the cell
     * 
     * @param int $increment
     * @return \Iris\Subhelpers\TableElement  (for fluent interface)
     */
    public function IncColspan($increment = 1) {
        $this->_colspan += $increment;
        return $this;
    }

    private function _formatCell() {
        $explodedContent = explode(Table::$StyleSeperator, $this->_content);
        switch (count($explodedContent)) {
            case 1:
                return [0, $this->_content];
            case 2:
                return $explodedContent;
            case 3:
        }
    }

}
