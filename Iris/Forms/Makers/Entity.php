<?php

namespace Iris\Forms\Makers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * Description of HandMade
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Entity extends \Iris\Forms\_FormMaker{
    
    protected static function _GetMaker($formName, $factoryType) {
        $maker = new static();
        $maker->setFactory($factoryType);
        $factory = $maker->getFormFactory();
        $maker->setForm($factory->createForm($formName));
        return $maker;
    }

    
    
}
