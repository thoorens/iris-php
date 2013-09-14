<?php

namespace Iris\models;

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
 * A way to obtain data from table "advertising"
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class TAdvertising extends \Iris\DB\_Entity{

    /**
     * The default language
     * @todo change to EN_ (when database is filled)
     */
    const DEFLANGUAGE = 'FR_';
    
    private static $_Model = \NULL;
    private static $_Language = \NULL;

    /**
     * An static initializer: two vars to init
     */
    public static function __ClassInit() {
        $client = new \Iris\System\Client();
        self::$_Language = $client->getLanguage();
        $em = \Iris\DB\_EntityManager::EMFactory('sqlite:/library/IrisInternal/iris/irisad.sqlite');
        self::$_Model = self::GetEntity($em);
    }

    /**
     *
     * @return type 
     */
    public static function getFeatures() {
        return self::getLines('_F%');
    }

    private static function getLines($selector){
        $modele = self::$_Model;
        $modele->whereLike('id', self::$_Language . $selector);
        $ads = $modele->fetchAll();
        if (count($ads) == 0) {
            $modele->whereLike('id', self::DEFLANGUAGE.$selector);
            $ads = $modele->fetchAll();
        }
        return $ads;
    }
    
    public static function getPlans() {
        return self::getLines('_P%');
    }

}

