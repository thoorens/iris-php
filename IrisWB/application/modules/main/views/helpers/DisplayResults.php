<?php

namespace Iris\views\helpers;
use Iris\controllers\helpers\StoreResults as SR;

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
 * @copyright 2011-2013 Jacques THOORENS
 *
 * 
 */

/**
 * Displays all the results of tests as a table consisting in explanations and results 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class DisplayResults extends \Iris\views\helpers\_ViewHelper {

    /**
     * There is only one instance of the class
     * 
     * @var boolean
     */
    protected $_singleton = TRUE;
    
    /**
     * This string receives all the output of render()
     * 
     * @var string
     */
    private $_html = '';
    
    /**
     * Changes to TRUE or FALSE according the position of the next text
     * in relation to &lt;tab> tags.
     * 
     * @var boolean
     */
    private $_inTable = \FALSE;

    /**
     * The class of the main tab tag
     * @var string
     */
    private $_tabClass = "show";
    
    /**
     * If true, forces each result to be displayed in two separate raws (default)
     * 
     * @var boolean
     */
    private $_twoRaws = \FALSE;

    /**
     * Returns the object or directly renders a result array
     * 
     * @return string
     */
    public function help($results = \NULL) {
        if (is_null($results))
            return $this;
        else
            return $this->render($results);
    }

    /**
     * Each result is displayed in one single raw
     */
    public function forceOneLine(){
        $this->_twoRaws = \FALSE;
    }
    
    /**
     * An set accessor for the table class (by default 'show')
     * 
     * @param string $class
     * @return \Iris\views\helpers\DisplayResults (fluent interface)
     */
    public function setTabClass($class) {
        $this->_tabClass = $class;
        return $this;
    }

    /**
     * Renders the result array as a long HTML text containing tabs
     * and titles
     * 
     * @param array[] $results
     * @return string (the final HTML string
     */
    public function render($results) {
        $this->_addStyle();
        foreach ($results as $result)
            switch ($result[SR::TYPE]) {
                case SR::GOOD:
                case SR::BAD:
                    $this->_displayResult($result);
                    break;
                case SR::TITLE:
                    $this->_closeTable();
                    $tag = 'h' . $result[SR::VALUE];
                    $this->_html .= "<$tag>" . $result[SR::MESSAGE] . "</$tag>" . CRLF;
                    break;
                case SR::PAUSE:
                    $this->_closeTable();
                    $this->_html .= '<br/>' . CRLF;
                    break;
            }
        $this->_closeTable();
        return $this->_html;
    }

    /**
     * Display a single result in one or two tab raw.
     * @param string[] $result
     */
    private function _displayResult($result) {
        $this->_openTable();
        if ($result[SR::STYLE] == SR::CODE) {
            $style = 'code';
            $openTT = '<tt>';
            $closeTT = '</tt>';
        }
        else {
            $openTT = $closeTT = '';
            $style = 'text';
        }
        $this->_html .= '   <tr>' . CRLF;
        $this->_html .= "       <td class=\"$style\">" . CRLF;
        $this->_html .= '           ' . $openTT . $result[SR::MESSAGE] . $closeTT . CRLF;
        $this->_html .= '       </td>' . CRLF ;
        $status = $result[SR::TYPE] == SR::GOOD ? 'good' : 'bad';
        $this->_separator();
        $this->_html .= "       <td class=\"$status\">" . CRLF;
        $this->_html .= '           ' . $result[SR::VALUE] . CRLF;
        $this->_html .= '       </td>' . CRLF . '    </tr>' . CRLF;
    }

    /**
     * If necessary, finishes a tab structure
     */
    private function _closeTable() {
        if ($this->_inTable) {
            $this->_inTable = \FALSE;
            $this->_html .= '</table>' . CRLF;
        }
    }

    /**
     * If not done already, opens a tab structure
     */
    private function _openTable() {
        if (!$this->_inTable) {
            $this->_inTable = \TRUE;
            $this->_html .= "<table class=\"$this->_tabClass\">" . CRLF;
        }
    }

    /**
     * Adds the inner styles for the result display
     */
    private function _addStyle() {
        $this->callViewHelper('styleLoader', "goodbadresult", <<<STYLE
 <style>
        table.show td{
            background-color: white;
        }
        table.show td.code{
            background-color: #FFE;
        }
        table.show td.bad{
            background-color:#FDD;
        }        
        table.show td.good{
            background-color:#DFD;
        }
        td.code tt{
            font-size:0.8em;
        }
        td.text        
    </style>           
STYLE
        );
    }

    private function _separator() {
        if($this->_twoRaws){
        $this->_html .= '    </tr>' . CRLF;
        $this->_html .= '   <tr>' . CRLF;
        }
    }

}
