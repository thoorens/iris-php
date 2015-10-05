<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 *
 *
 * Creates a button which links to a page or site
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ImageLink extends _ViewHelper {

    const BLANKSTRING = \Iris\Subhelpers\_SuperLink::BLANKSTRING;

    /**
     * @param string $label link label
     * @param string $url target URL
     * @param string $tooltip tooltip when mousevoer
     * @param string $class class of the link
     * @param string $id id of the linklabel
     * @return string
     */
    public function help($label = self::BLANKSTRING, $url = self::BLANKSTRING, $tooltip = self::BLANKSTRING, $class = self::BLANKSTRING, $id = self::BLANKSTRING) {
        $args = func_get_args();
        $imageLink = new \Iris\Subhelpers\ImageLink($args);
        $imageLink->setNoLink(\TRUE);
        return $imageLink;
    }

}
