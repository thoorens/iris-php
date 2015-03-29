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
class Link extends \Iris\Subhelpers\Link {

    protected static $_Instance = NULL;

    protected function _makeButton() {
        if (\Iris\Users\Session::JavascriptEnabled() and !self::$NoJavaForce) {
            $this->addAttribute('data-dojo-type', 'dijit/form/Button');
        }
        $html = parent::_makeButton();
        $this->removeAttribute('data-dojo-type');
        return $html;
    }

}

