<?php

namespace Iris\Subhelpers;

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
 * @copyright 2011-2014 Jacques THOORENS
 */

/**
 * This class is a subhelper for the helper Link family.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Link extends \Iris\Subhelpers\_SuperLink {

    public function __toString() {
        if ($this->_nodisplay) {
            $this->_image = \FALSE;
            return '';
        }
        $this->_renderImage();
        $attributes = $this->_renderAttributes();
        $this->_image = \FALSE;
        return sprintf('<a href="%s" %s >%s</a>', $this->getUrl(), $attributes, $this->getLabel());
    }

    /**
     * Permits to change a link into a button at last stage
     * 
     * @return string
     */
    public function button(){
        $button = Button::GetInstance();
        return $button->autorender($this->getLabel(),$this->getUrl(),$this->getTooltip());
    }
}
