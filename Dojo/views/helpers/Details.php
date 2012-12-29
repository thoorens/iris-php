<?php

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

namespace Dojo\views\helpers;

/**
 * Manages a "details" button or html zone which displays a hidden class
 * when clicked.
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Details extends _DojoHelper {

    protected $_id = 'det';
    
    /**
     * The class who must mask the text (something like "display:none;"
     * 
     * @var string 
     */
    private $_maskClass;
    
    /**
     * An optional class to format text when it is being viewed
     * @var string 
     */
    private $_aspectClass = '';
    /**
     * A sequential number for avoiding clash when there is multiple
     * details section on same page
     * 
     * @var int
     */
    protected $_num = 0;
    /**
     * An HTML string for displaying the sensible zone
     * 
     * @var string
     */
    protected $_toggler = '<span id="%s" class="dojoIrisTitle"> %s </span>';
    /**
     * The tag (by default 'div') used to mark the hidden zone
     * 
     * @var string
     */
    private $_tag = 'div';

    /**
     * During creation, you must provide a mask class name
     * and if the helper is inited out of a view, the name to
     * get it back in the view.
     * 
     * @param string $maskClass The class used to hide text
     * @param string $varName an optional variable name
     * @return Details 
     */
    public function help($maskClass='dojoIrisMask', $varName=NULL) {
        $this->_maskClass = $maskClass;
        if (!is_null($varName)) {
            $this->_view->$varName = $this;
        }
        return $this;
    }

    /**
     * Accessor set for the class formating the text while being viewed
     * 
     * @param string $aspectClass
     * @return Details 
     */
    public function setAspectClass($aspectClass) {
        $this->_aspectClass = $aspectClass;
        return $this;
    }

    /**
     * Accessor set for the tag surrounding the masked text
     * @param string $tag 
     */
    public function setTag($tag) {
        $this->_tag = $tag;
    }

    
    public function getId() {
        return $this->_id."_";
    }

    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

        
    /**
     * A way to change the sensible zone: it's a format string with
     * two parameters %s : the first (required) is the id of toggler,
     * the second will receive the html text displayed in the zone
     * 
     * @param string $toggler 
     */
    public function setToggler($toggler) {
        $this->_toggler = $toggler;
    }

    /**
     * Produce the final display with three parts: a beginning, a sensible zone
     * and a hidden part.
     * 
     * @param string $beginning the optional visible part of the text (e.g. a title)
     * @param string $details the hidden part of the text
     * @param string $label the content of the sensible zone (by def. "Details" localized
     * @return type 
     */
    public function details($beginning, $details, $label="(Details)") {
        $num = ++$this->_num;
        if ($label == NULL) {
            
        }
        else {
            $det = $this->getId();
            $toggler = sprintf($this->_toggler, "tog_$det$num",$this->_($label,TRUE));
            $html = <<<HTML
$beginning
$toggler
<$this->_tag id="$det$num" class="$this->_maskClass $this->_aspectClass">$details
</$this->_tag>
HTML;
        }
        return $html;
    }

    /**
     *
     * @param type $event
     * @param type $sensibleClass
     * @return string 
     */
    public function connectEvent($event) {
        $det = $this->getId();
        $html = '<script type="text/javascript">' . "\n";
        $html .= 'dojo.addOnLoad(function() {' . "\n";
        for ($i = 1; $i <= $this->_num; $i++) {
            $html .= sprintf('dojo.connect(dojo.byId("%s"), "%s",function (e){dojo.toggleClass("%s", "%s")});' . "\n", 
                    'tog_'.$det.$i, $event, "$det$i", $this->_maskClass);
        }
        $html .="});\n</script>\n";
        return $html;
    }
    
    public function connectEvents($events){
        $html = '';
        foreach($events as $event){
            $html .= $this->connectEvent($event);
        }
        return $html;
    }
}

