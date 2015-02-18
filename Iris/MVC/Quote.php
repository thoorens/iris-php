<?php

namespace Iris\MVC;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS/*
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
        $template = new Template(\NULL);
        $template->setText($this->_textTemplate);
        return $template;
    }

    

}

