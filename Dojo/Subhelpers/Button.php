<?php

namespace Dojo\Subhelpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This class is a subhelper for the dojo helper Link family. 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Button extends \Iris\Subhelpers\Button {

    
    protected function _javascriptButton() {
        $this->addAttribute('data-dojo-type', 'dijit/form/Button');
        $html = parent::_javascriptButton();
        //$this->removeAttribute('data-dojo-type');
        return $html;
    }

    
    /**
     * @see https://jsfiddle.net/zrdj0gp0/#&togetherjs=LqddASq2QB
     */
    public function setId($id) {
        $this->_attributes['data-dojo-id'] = $id;
        return parent::setId($id);
    }

    
    protected function _render() {
        if ($this->_empty($this->getLabel())) {
            $html = '';
        }
        elseif (!\Iris\Users\Session::JavascriptEnabled() or self::$NoJavaForce) {
            if (\Iris\System\Client::OldBrowser(self::$OldBrowser)) {
                $html = $this->_simulatedButton();
            }
            else {
                $html = $this->_linkButton();
            }
        }
        else {
            $html = $this->_javascriptButton();
        }
        return $html;
    }

}
