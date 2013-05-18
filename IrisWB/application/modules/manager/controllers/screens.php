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
 */
class screens extends _manager {

    use \Iris\DB\DataBrowser\tCrudManager;

    public function indexAction($section = 10) {
        $icons = \Iris\Subhelpers\Crud::getInstance();
        $icons
                // définition du contrôleur
                ->setController('/manager/screens')
                // définition du préfixe d'action (on aura par exemple insert_media)
                ->setActionName('screen')
                // précision du genre de l'entité (M, F ou M' F' pour les élisions)
                // et de son intitulé
                ->setEntity("M'_écran")
                // champ de l'intitulé servant à décrire l'objet affecté
                ->setDescField('Description')
                // champ constituant la clé primaire
                ->setIdField('id');
        $tSequence = \Iris\DB\_Entity::GetEntity('sequence');
        $tSequence->where('section_id=', $section);
        $screens = $tSequence->fetchAll();
        $this->__screens = $screens;
        $this->__category = $screens[0]->_at_section_id->GroupName;
    }

    protected function _customize($actionName) {
        $parameters = $this->getResponse()->getParameters();
        $tSequence = \Iris\DB\_EntityManager::GetEntity('sequence');
        $section = $tSequence->find($parameters[0])->section_id;
        if (!is_null($section))
            $this->__section = $section;
        else
            $this->__section = 0;
    }

}
