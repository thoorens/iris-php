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
 * This subhelper will create an html table line
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TableLine {

    /**
     * a line tag
     */
    const TR = 'tr';

    /**
     * the content of a cell may a cell or a way go to previous cell or line
     * @var TableElement[]
     */
    private $_content;

    /**
     * a optional class for the element
     * @var string
     */
//    private $_class = '';

    /**
     * an optional id for the element
     * @var string
     */
//    private $_id = '';

    /**
     * 
     * @param type $content
     * @return \Iris\Subhelpers\TableElement
     */
    private function __construct($content) {
        $this->_content = $content;
        return $this;
    }

    /**
     * a factory method to create a line of cells
     * 
     * @param Table $table
     * @param int $lineNumber
     * @param array $content
     * @return type
     */
    public static function CreateLine($lines, $content, $lineNumber, $type) {
        $lastCell = \NULL;
        foreach ($content as $cellNumber => $cellContent) {
            switch ($cellContent) {
                case Table::$ColMarker:
                    $cells[$cellNumber] = new TableElement('COL', TableElement::NOCELL);
                    if ($lastCell !== \NULL) {
                        $lastCell->IncColspan();
                    }
                    break;
                case Table::$RawMarker:
                    //i_d($lineNumber);
                    $cells[$cellNumber] = new TableElement('RAW', TableElement::NOCELL);
                    while($lineNumber>0){ //will force a break if data error
                        $lineNumber--;
                        $line = $lines[$lineNumber];
                        $cell =$line->getElement($cellNumber);
                        if($cell !== 0 and $cell->getType() !== TableElement::NOCELL){
                            $cell->IncRowspan();
                            break;
                        }
                    }
                    //$table->getLine($lineNumber - 1)->getElement($cellNumber)->incRowspan();
                    break;
                case Table::$RawColMarker:
                case Table::$ColRawMarker:
                    $cells[$cellNumber] = new TableElement('COLRAW', TableElement::NOCELL);
                    //$table->getLine($lineNumber - 1)->getElement($cellNumber)->incRowspan();
                    break;
                default:
                    $lastCell = new TableElement($cellContent, $type);
                    $cells[$cellNumber] = $lastCell;
            }
        }
        return new TableLine($cells);
    }

    /**
     * a renderer for the line
     * 
     * @return string
     */
    public function render() {
        foreach ($this->_content as $cel) {
            $celRender = $cel->render();
            if ($celRender !== '') {
                $content[] = $celRender;
            }
        }
        return "\t\t<tr>\n" . implode("\n", $content) . "\n\t\t</tr>";
    }

    /**
     * 
     * @param type $elementNumber
     * @return TableElement
     */
    public function getElement($elementNumber) {
        return $this->_content[$elementNumber];
    }

    /**
     * Change the class of the line
     * 
     * @param string $class
     * @return \Iris\Subhelpers\TableLine   for fluent interface
     */
//    public function setClass($class) {
//        $this->_class = $class;
//        return $this;
//    }
//    public function setId($id) {
//        $this->_id = $id;
//        return $this;
//    }
//    public function render() {
//        foreach($this->_content as $cel){
//            $cel->render();
//        }
//    }
}
