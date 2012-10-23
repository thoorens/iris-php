<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tutorial\controllers;

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
 * Description of TutoPage
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class tutoPage extends \Iris\MVC\_Islet{
    use \Tutorial\Translation\tSystemTranslatable;
    
    public function indexAction(\Tutorial\Content\Item $item){
        //iris_debug($para);
        // Fixing scripName is mandatory for islet in internal library
        // Note the funny second part: it will find tutoPage_index.iview in the good library. 
        // Only the second part of the name has to be stated.
        $this->setViewScriptName('Tutorial#_index');
        $this->__title = $item->getTitle();
        $id = $item->getId();
        $this->_view->dojo_container("tabs$id", 'Tab')
                ->setDefault("show$id")
                ->setDim(680,960)
                ->setItems([
            "show$id" => 'Tuto',
            "explanations$id" => $this->_('Explanations'),
        ]);
        $this->__text = $item->getText();
        $this->__page = $item->getPage();
        $this->__id = $id;
    }
}
?>
