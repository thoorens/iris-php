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
 * @copyright 2012 Jacques THOORENS
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

    public function indexAction() {
        $this->setViewScriptName('add');
        $this->_setLayout('add');
        $features = \Iris\models\TAvertising::getFeatures();
        $this->__features = $features;
        $plans = \Iris\models\TAvertising::getPlans();
        $this->__plans = $plans;
    }

    public function addAction() {
        
    }

}
