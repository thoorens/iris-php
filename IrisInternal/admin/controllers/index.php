<?php

namespace IrisInternal\admin\controllers;

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
 * 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class index extends _admin {

    public function preDispatch() {
        //$this->_setLayout('iris_shadow');
        $client = new \Iris\System\Client();
        $language = $client->getLanguage();
        $this->__language = $language;
    }

    /**
     * Admin welcome some explanation and a menu
     */
    public function indexAction() {
        $this->__buttons = $this->adminButtons();
    }

    public function cleanAction() {
        $scanner = new \Iris\Admin\Scanner();
        $contenu = $scanner->clean('main/controllers/index.php');
        $this->message = $contenu;
    }

    public function scanAction() {
        $this->__ = $this->_view->dojo_Mask();
        $scanner = new \Iris\Admin\Scanner();
        $scanner->scanApplication();
        die('ok');
        $modules = $scanner->collect();
        iris_debug($modules);
        $this->__modules = $modules;
        // action loop
    }

    public function dbtestAction($class='simple') {
        $test = new \Iris\Admin\models\TTest($class);
        $this->meta = $test->getMetadata();
    }

    public function cssAction() {
        $this->_view->cssAddition('testcss.css');
    }

}
