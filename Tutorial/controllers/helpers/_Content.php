<?php

namespace Tutorial\controllers\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * Generates a Screen object with all the informations necessary to
 * load the resources for one screen : images, text, voice and synchonisation.
 * The content may a simple image (IMAGE), a simple (VIEW) or a simple text (TEXT)
 * or both a text and a view, displayed in tabs (TEXTVIEW)
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 * @deprecated since version 2015
 */
abstract class _Content extends \Iris\controllers\helpers\_ControllerHelper {

    /**
     * Returns an new completed item.
     * 
     * @param int $num
     * @return \Tutorial\Content\Screen
     */
    public function help($num) {
        return $this->getItem($num);
    }

    /**
     * The method will be overwritten in each concret tutorial
     * 
     * @return \Tutorial\Content\Screen
     */
    protected abstract function getItem($num);
}

