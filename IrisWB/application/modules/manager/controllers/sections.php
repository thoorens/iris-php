<?php
namespace modules\manager\controllers;

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
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of categories
 * 
 * @author jacques
 * @license not defined
 */
class sections extends _manager {
use \Iris\DB\DataBrowser\tCrudManager;


    
    public function indexAction() {
        $icons = \Iris\Subhelpers\Crud::getInstance();
        $icons
                // définition du contrôleur
                ->setController('/manager/sections')
                // définition du préfixe d'action (on aura par exemple insert_media)
                ->setActionName('section')
                // précision du genre de l'entité (M, F ou M' F' pour les élisions)
                // et de son intitulé
                ->setEntity("F_section")
                // champ de l'intitulé servant à décrire l'objet affecté
                ->setDescField('GroupName')
                // champ constituant la clé primaire
                ->setIdField('id');
        $tSection = \Iris\DB\_Entity::GetEntity('sections');
        $tSection->where('id<>', 0);
        $this->__data = $tSection->fetchAll();
    }

    
}
