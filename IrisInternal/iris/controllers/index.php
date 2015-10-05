<?php

namespace IrisInternal\iris\controllers;

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
 * @copyright 2011-2013 Jacques THOORENS
 */

/**
 * A commercial screen to present Iris features
 */
class index extends \IrisInternal\main\controllers\_SecureInternal {

    /**
     * no security required
     */
    public function security() {
    }

    /**
     * Prepares a screen in French by default, if not otherwise specified
     * 
     * @param sting $language The language to use (only french and english are supported)
     */
    public function indexAction($language = 'fr') {
        \Iris\SysConfig\Settings::$DisplayRuntimeDuration = \FALSE;
        $language = strtolower($language);
        if(strpos('fr-en',$language)===\FALSE){
            $language ='inter';
        }
        $this->setViewScriptName($language);
        $this->_setLayout('add');
        $features = \Iris\models\TAdvertising::getFeatures();
        $this->__features = $features;
        $plans = \Iris\models\TAdvertising::getPlans();
        $this->__plans = $plans;
        $this->__image1 =  'logoV.jpg';
        $this->__comment1 =  'Iris-PHP logo';
    }


    /**
     * This action is placed to test private screens
     */
    public function prohibitedAction(){
        $this->displayError(\Iris\Errors\Settings::TYPE_PRIVILEGE);
    }
    
    /**
     * This action is intended to create a fatal error
     * for testing purpose.
     */
    public function fatalAction(){
        $i = 10/0;
    }
}
