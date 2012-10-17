<?php

namespace Iris\MVC;

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
 * A "Quote" is a partial view whose text is not in a standard
 * file of type .iview. It can be generated or found in a database.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * A "Quote" is a view whose text is not in a standard
 * file of type .iview. It can be generated or found in a database.
 * 
 */
class Quote extends Partial {

    /**
     * Type of view
     * @var string
     */
    protected static $_ViewType = 'quoted view';

    /**
     * The text template to be returned
     * @var string
     */
    private $_textTemplate = array();

    /**
     * View error mechanism uses the script name. There is none here.
     * 
     * @var string
     */
    protected static $_LastUsedScript = 'Quoted string';

    /**
     * The constructor associates a template and the data to be inserted into it
     * 
     * @param type $text The text template to be displayed
     * @param mixed $data The data to be put in the template
     */
    public function __construct($text,$data) {
        $this->_transmit($data);
        $this->addTextTemplate($text);
    }

    /**
     * Add a line to the text template
     * 
     * @param string $text 
     */
    public function addTextTemplate($text) {
        $this->_textTemplate[] = $text;
    }

    /**
     * Get the text template as a long string
     * 
     * @param type $scriptName will be always NULL
     * @return string
     */
    protected function _getTemplate($scriptName) {
        //iris_debug($this->_textTemplate);
        return $this->_textTemplate;
    }

    

}

