<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Iris\controllers\helpers;

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
 * Description of newIrisClass
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class MakeForm extends _ControllerHelper {

    /**
     * 
     * @param type $fields
     * @return \Iris\Forms\_Form
     */
    public function help($fields) {
        $all = count($fields) == 0;

        $formFactory = \Iris\Forms\_FormFactory::GetDefaultFormFactory();


        $form = $formFactory->createForm('Test');
        
        if ($all or in_array('concert', $fields)) {
            $formFactory->createText('Nom')
                    ->addTo($form)
                    ->setLabel("Nom de l'événement:");
        }
        
        if ($all or in_array('organisateur', $fields)) {
            $formFactory->createText('Organisateur')
                    ->setLabel('Organisateur du spectacle')
                    ->addTo($form);
        }

        if ($all or in_array('dateConcert', $fields)) {
            $formFactory->createDate('DateConcert')
                    ->setLabel('Date du concert:')
                    ->setTitle("A préciser uniquement si elle est déjà connue")
                    ->addTo($form);
        }
        if ($all or in_array('dateDebut', $fields)) {
            $formFactory->createDate('DateDebut')
                    ->setLabel('Début du festival:')
                    ->setTitle('Le festival est défini dans une fourchette de date')
                    ->addTo($form);
        }
        if ($all or in_array('dateFin', $fields)) {
            $formFactory->createDate('DateFin')
                    ->setLabel('Fin du festival:')
                    ->setTitle('Le festival est défini dans une fourchette de date')
                    ->addTo($form);
        }
        if ($all or in_array('heureDebut', $fields)) {
            $formFactory->createTime('HeureDebut')
                    ->setLabel('Début du concert:')
                    ->setTitle('Début de la prestation du groupe')
                    ->addTo($form);
        }if ($all or in_array('heureFin', $fields)) {
            $formFactory->createTime('HeureFin')
                    ->setLabel('Fin du concert:')
                    ->setTitle('Fin de la prestation du groupe')
                    ->addTo($form);
        } if ($all or in_array('heureArrivee', $fields)) {
            $formFactory->createTime('HeureArrrivee')
                    ->setLabel('Début du festival:')
                    ->setTitle('Début des obligations (soundcheck?)')
                    ->addTo($form);
        }
        if ($all or in_array('heureDepart', $fields)) {
            $formFactory->createTime('HeureDepart')
                    ->setLabel('Début du festival:')
                    ->setTitle('Fin des obligations (hors trajet)')
                    ->addTo($form);
        }

        // Localisation
        if ($all or in_array('lieu', $fields)) {
            $formFactory->createText('Lieu')
                    ->setLabel('Lieu:')
                    ->setTitle('Emplacement du concert')
                    ->addTo($form);
        }
        if ($all or in_array('adresse', $fields)) {
            $formFactory->createText('Adresse')
                    ->setLabel('Adresse:')
                    ->setTitle('Adresse précise du concert')
                    ->addTo($form);
        }
        if ($all or in_array('lien', $fields)) {
            $formFactory->createUrl('Lien')
                    ->setLabel('URL site du festival:')
                    ->setTitle('URL du site du festival')
                    ->addTo($form);
        }
        if ($all or in_array('etiquette', $fields)) {
            $formFactory->create('Etiquette')
                    ->setLabel('Etiquette:')
                    ->setTitle('Texte pour le lien (si vide http:...')
                    ->addTo($form);
        }

        //Group
        if ($all or in_array('groups', $fields)) {
            $data = [1=>'Windows',2=>'Linux',4=>'MacOS'];
            // The array values are used to set the names and select the
            // choosed value (see TRUE in addOptions)
            $formFactory->createRadioGroup('Os1')
                    ->setPerLine(3)
                    ->addTo($form)
                    ->setLabel('Radio group (by content):')
                    ->setValue('Linux')
                    ->setTitle("Choose your prefered operating system")
                    ->addOptions($data, \TRUE);
            // The array index are used to set the names and select the
            // choosed value (see FALSE in addOptions)
            $formFactory->createRadioGroup('Os2')
                    ->setPerLine(3)
                    ->addTo($form)
                    ->setLabel('Radio group (by index):')
                    ->setValue(2)
                    ->setTitle("Choose your prefered operating system")
                    ->addOptions($data, \FALSE);
            $formFactory->createSelect('$Os3')
                    ->addTo($form)
                    ->setLabel('Select option:')
                    ->addOptions($data)
                    ->setTitle("Choose your prefered operating system")
                    ->setValue(2);
            $formFactory->createMultiCheckbox('Os3')
                    ->addTo($form)
                    ->setLabel('Multicheck group:')
                    ->addOptions($data)
                    ->setTitle("Choose your prefered operating system")
                    ->setValue(2+4);
            $formFactory->createButtonGroup('Os4')
                    ->addTo($form)
                    ->setLabel('Button group:')
                    ->setTitle("Choose your prefered operating systems")
                    ->addOptions($data);
        }


        $formFactory->createSubmit('Submit')->addTo($form);
        //$form->setLayout(new \Iris\Forms\TabLayout());
        return $form;
    }

}

?>
