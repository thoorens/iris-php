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
 * This class is a subhelper for the helper Link family.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Link extends \Iris\Subhelpers\_SuperLink {

    protected static $_Type = self::LINK;
    
    public function _render() {
        $attributes = $this->_renderAttributes();
        $label = $this->getLabel();
        $url = $this->_renderUrl(\TRUE);
        return sprintf('<a %s %s >%s</a>', $url, $attributes, $label);
    }
    
    /**
     * This method throws an exception if called with a link object
     *
     * @param type $url
     * @throws \Iris\Exceptions\BadLinkMethodException
     */
    public function link($url = self::BLANKSTRING) {
        throw new \Iris\Exceptions\BadLinkMethodException('A link cannot be transformed into a link');
    }

    /**
     * Transforms a link or an image to a button
     * 
     * @param type $url
     * @return \Iris\Subhelpers\Button
     */
    public function button($url = self::BLANKSTRING){
        $button = new Button([]);
        $this->_copyData($button);
        if($this->_used($url)){
            $button->setUrl($url);
        }
        return $button;
    }

}
