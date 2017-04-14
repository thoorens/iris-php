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
 * This subhelper will create an html table
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Table extends _FlexibleSubhelper {

    /*
     * No caption for the table
     */
    const NO_CAPTION = 'NONE';
    /**
     * Caption on top
     */
    const CAPTION_TOP = 'top';
    /**
     * Caption at bottom
     */
    const CAPTION_BOTTOM = 'bottom';
    /**
     * Caption at the left side (dangerous)
     */
    const CAPTION_LEFT = 'left';
    /**
     * Caption at the right side (dangerous)
     */
    const CAPTION_RIGHT = 'right';

    /**
     * A marker to indicate an overlay from the previous line
     * 
     * @var string
     */
    public static $RawMarker = '|';

    /**
     * A marker to indicate an overlay from the previous cell
     * 
     * @var string 
     */
    public static $ColMarker = '_';

    /**
     * A dummy marker to indicate an overlay from the previous cell and the previous line
     * 
     * @var string
     */
    public static $ColRawMarker = '_|';

    /**
     * A synonym marker for '_|'
     * 
     * @var string 
     */
    public static $RawColMarker = '|_';

    /**
     * A separator between class, id and content
     * 
     * @var string
     */
    public static $StyleSeperator = "µ";

    /**
     *
     * @var type 
     */
    public static $TableCSS = '/!documents/file/css/irisTable.css';
    public static $CSSFile = [];

    /**
     * A special content (see SetSpecial())
     * @deprecated since version nov 2016
     * @var string
     */
    protected static $_SpecialLine = "__SPECIAL__";

    /**
     * The values of the body part of the table (2 dimension array)
     * The cells covered by column and raw span are marked as NOCELL
     *  
     * @var mixed[]
     */
    protected $_bodyCells = [];

    /**
     * The values of the head part of the table (2 dimension array)
     * The cells covered by column and raw span are marked as NOCELL
     * 
     * @var mixed 
     */
    protected $_headCells = [];

    /**
     * indicates if it is necessary to add thead and tbody
     * 
     * @var boolean
     */
    protected $_headBody = \TRUE;

    /**
     * The caption text of the table
     * 
     * @var string 
     */
    protected $_caption;

    /**
     * The position of the caption
     * 
     * @var int
     */
    protected $_position = self::NO_CAPTION;

    /**
     * The class name associated with the table
     * 
     * @var string
     */
    protected $_class = "demo";

    /**
     * The id name associated with the table
     * 
     * @var string 
     */
    protected $_id = '';

    /**
     * Creates the object and initialises the class name (optionally the id)
     * 
     * @param string $class the name of the class (demo by default)
     * @param string $id the name of the optional id
     */
    public function __construct($class = 'demo', $id = '') {
        $this->_class = $class;
        $this->_id = $id;
    }

    /**
     * A setter for the class name (if empty string no class)
     * 
     * @param string  $class
     * @return \Iris\Subhelpers\Table for fluent interface
     */
    public function setClass($class) {
        $this->_class = $class;
        return $this;
    }

    /**
     * A setter for the id name (if empty string no id)
     * @param type $id
     * @return \Iris\Subhelpers\Table for fluent interface
     */
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    /**
     * @todo VERIFY IF OBSOLETE : in ConversionAnimation on u3a.be
     * @param type $name
     * @deprecated since version 2016 october
     */
    public static function SetSpecial($name) {
        static::$_SpecialLine = $name;
    }

    /**
     * Fills the body part of the table from a two dimension array
     * 
     * @param string[] $array
     * @return \Iris\Subhelpers\Table for fluent interface
     */
    public function setContent($array) {
        $this->_bodyCells = $this->_insertContent($array, TableElement::TD);
        //i_d($this->_bodyCells);
        return $this;
    }

    /**
     * Fills the head part of the table from a two dimension array
     * 
     * @param string[] $array
     * @return \Iris\Subhelpers\Table for fluent interface
     */
    public function setTitles($array) {
        $array = is_array($array[0]) ? $array : [$array];
        $this->_headCells = $this->_insertContent($array, TableElement::TH);
        return $this;
    }

    /**
     * Change the raw spanning marker and adapt RowCol markers
     * 
     * @param string $rawMark the new marker for raw
     */
    public static function SetRawMark($rawMark) {
        self::$RawMarker = $rawMark;
        self::$RawColMarker = self::$RawMarker . self::$ColMarker;
        self::$ColRawMarker = self::$ColMarker . self::$RawMarker;
    }

    /**
     * Changes the column spanning marker and adapt RowCol markers
     * 
     * @param string $colMark the new marker for column
     */
    public static function SetColMark($colMark) {
        self::$ColMarker = $colMark;
        self::$RawColMarker = self::$RawMarker . self::$ColMarker;
        self::$ColRawMarker = self::$ColMarker . self::$RawMarker;
    }

    /**
     * Reads the class and id separator
     * 
     * @return string
     */
    public static function GetStyleSeperator() {
        return self::$StyleSeperator;
    }

    /**
     * Changes the class and id separator
     * 
     * @param string $StyleSeperator
     */
    public static function SetStyleSeperator($StyleSeperator) {
        self::$StyleSeperator = $StyleSeperator;
    }

    /**
     * Sets the file name containing the styles used in the table
     * 
     * @param string $TableCSS
     */
    public static function SetTableCSS($TableCSS) {
        self::$TableCSS = $TableCSS;
    }

        
    /**
     * Organizes a two dimension array in table lines or titles
     * 
     * @param string[] $array the lines (in an array)
     * @param string $type (the type of cells)
     * @return TableLine[] the lines consisting of cells 
     */
    private function _insertContent($array, $type) {
        $lines = [];
        foreach ($array as $index => $line) {
            $newLine = TableLine::CreateLine($lines, $line, $index, $type);
            $lines[$index] = $newLine;
        }
        return $lines;
    }

    /**
     * Permits to allow or prohibit thead and tbody
     * 
     * @param type $headBody
     * @return \Iris\Subhelpers\Table
     */
    public function setHeadBody($headBody = \TRUE) {
        $this->_headBody = $headBody;
        return $this;
    }

    /**
     * Permits to define the caption of the table
     * 
     * @param string $text The text to be displayed in the caption
     * @return \Iris\Subhelpers\Table  for fluent interface
     */
    public function setCaption($text, $position = self::CAPTION_TOP) {
        $this->_caption = $text;
        $this->_position = $position;
        return $this;
    }

    /**
     * Renders the complete table
     * 
     * @return string
     */
    public function render() {
        if(!isset(self::$CSSFile['tableCSS'])){
            \Iris\views\helpers\StyleLoader::FunctionCall(['tableCSS', self::$TableCSS]);
            self::$CSSFile['tableCSS'] = 1;
        }
        $class = $this->_class === BLANKSTRING ? '' : " class =\"$this->_class\" ";
        $id = $this->_id === BLANKSTRING ? '' : " id =\"$this->_id\" ";
        $html[] = sprintf("\n<table%s%s>", $class, $id);
        $html[] = $this->_renderCaption();
        $headBody = $this->_headBody;
        // if no head cells, no thead nor tbody
        if (count($this->_headCells) === 0) {
            $headBody = \FALSE;
        }
        else {
            $headBody and $html[] = "\t<thead>";
            foreach ($this->_headCells as $line) {
                $html[] = $line->render();
            }
            $headBody and $html[] = "\t</thead>";
            $headBody and $html[] = "\t<tbody>";
        }
        foreach ($this->_bodyCells as $line) {
            $html[] = $line->render();
        }
        $headBody and $html[] = "\t</tbody>";
        $html[] = "</table>\n";
        return implode("\n", $html);
    }

    /**
     * Returns the HTML lines for a caption (may be empty)
     * 
     * @return string
     */
    protected function _renderCaption() {
        $position = $this->_position;
        switch ($position) {
            case self::NO_CAPTION:
                $value = '';
                break;
            case self::CAPTION_TOP:
                $value = "\t<caption>$this->_caption</caption>";
                break;
            case self::CAPTION_BOTTOM:
                $value = "\t<caption style=\"caption-side:$position; font-style: italic;\">" . $this->_caption . '</caption>';
                break;
            case self::CAPTION_LEFT:
                $value = "\t<caption style=\"caption-side:$position; font-style: italic;\">" . $this->_caption . '</caption>';
                break;
            case self::CAPTION_RIGHT:
                $value = "\t<caption style=\"caption-side:$position; font-style: italic;\">" . $this->_caption . '</caption>';
                break;
        }
        return $value;
    }

    /**
     * Converts the object to its string representation (magick method needed by the helper)
     * 
     * @return string
     */
    public final function __toString() {
        return $this->render();
    }

}
