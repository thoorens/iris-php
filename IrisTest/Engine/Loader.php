<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace IrisTest\Engine;

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
 * Description of Loader
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Loader {

    public static function DoTest($number,$options=array()) {

        $loader = \Iris\Engine\Loader::GetInstance();

        switch ($number) {
            case 1:
                \Iris\Log::AddDebugFlag(\Iris\Engine\Debug::LOADER);
                // Normal classes
                $classes = array(
                    'Iris\Forms\_Form',
                    'Dojo\Forms\FormFactory',
                    );
                foreach($options as $option){
                    $classes[] = 'modules\\main\\controllers\\'.$option;
                }
                foreach ($classes as $class) {
                    echo "<b>$class<br></b>";
                    $loader->loadClass($class);
                };
                break;

            case 2:
                $classes = array(
                    'Dojo_Body',
                    'Title',
                    'Reseaux',
                    'Heures',
                );
                \Iris\Log::AddDebugFlag(\Iris\Engine\Debug::HELPER);
                foreach ($classes as $class) {
                    echo "<b>$class<br></b>";
                    \Iris\views\helpers\_ViewHelper::HelperCall($class, array(), \NULL);
                }
                break;
        }

        die('stop');
    }

}

