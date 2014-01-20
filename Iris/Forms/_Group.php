<?php

namespace Iris\Forms;

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
 * An abstract group with common methods for other concertes groups
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Group extends _Element implements iFormContainer {

    protected $_components = array();
    protected $_groupTag;

    /**
     *
     * @var string 
     */
    protected $_name;

    public function __construct($name, iFormContainer $container) {
        $this->_name = $name;
        $container->addElement($this);
        $this->_container = $container;
    }

    public function addElement(_Element $element) {
        $this->_components[$element->getName()] = $element;
        $this->_container->registerElement($element);
    }

    public function makeReadOnly() {
        foreach ($this->_components as $element) {
            if ($element->canDisable()) {
                $element->setDisabled('disabled');
            }
        }
    }

    public function registerElement(_Element $element) {
        $this->_container->registerElement($element);
    }

    

    public function render($layout=\NULL) {
        if (is_null($layout)) {
            $layout = $this->getLayout();
        }
        $html[] = "<$this->_groupTag>";
        $html[] = $this->_specificRender($html);
        $html[] = $layout->getGroupEntry(' class="group"');
        foreach ($this->_components as $component) {
            $html[] = $component->render();
        }
        $html[] = $layout->getGroupExit();
        $text = implode("\n\t", $html);
        $text .= "\n\t</$this->_groupTag>";
        return $text;
    }

    public function _specificRender(&$html) {
        
    }

}


