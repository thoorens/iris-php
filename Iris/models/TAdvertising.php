<?php

namespace Iris\models;
/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
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
    
    /**
     *
     * @var \Iris\DB\_Entity
     */
    private static $_Model = \NULL;
    private static $_Language = \NULL;

    /**
     * An static initializer: two vars to init
     */
    public static function __ClassInit() {
        $client = new \Iris\System\Client();
        self::$_Language = $client->getLanguage();
        $em = \Iris\DB\_EntityManager::EMByNumber(\Iris\SysConfig\Settings::$AdDatabaseNumber);
        //$em = \Iris\DB\_EntityManager::EMFactory('sqlite:library/IrisInternal/iris/irisad.sqlite');
        iris_debug($em);
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

