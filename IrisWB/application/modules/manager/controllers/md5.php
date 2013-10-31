<?php

namespace modules\manager\controllers;

use \Iris\views\helpers\Signature;

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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * This small class implements a way to record the md5 checksum of each
 * screen of WB in the inner database
 * 
 */

class md5 extends _manager {

    /**
     * This action saves the md5 chacksum corresponding to the module/controller/action
     * passed as parameters
     * 
     * @param string $module the module part of the URL
     * @param string $controller the controller part of the URL
     * @param string $action the action part of the URL
     * @param string $md5 the md5 value
     */
    public function saveAction($module, $controller, $action, $md5) {
        $url = "/$module/$controller/$action";
        $tSequences = \models_internal\TSequence::GetEntity();
        $fields = Signature::GetModelFields();
        $URLField = $fields[Signature::URL];
        $md5Field = $fields[Signature::MD5];
        $screen = $tSequences->fetchRow("$URLField=", $url);
        $screen->$md5Field = $md5;
        $screen->save();
        // go to precedent screen
        $this->reroute($url);
    }

}
