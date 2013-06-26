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
class MakeGroupForm extends _ControllerHelper {

    /**
     * 
     * @param \Iris\Forms\_FormFactory $formFactory
     * @return type
     */
    public function help($formFactory) {

        $form = $formFactory->createForm('test');

        $data = [1 => 'Windows', 2 => 'Linux', 4 => 'MacOS'];
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
                ->setValue(2 + 4);
        $formFactory->createButtonGroup('Os4')
                ->addTo($form)
                ->setLabel('Button group:')
                ->setTitle("Choose your prefered operating systems")
                ->addOptions($data);




        $formFactory->createSubmit('Submit')->addTo($form)->setValue('Send');
        return $form;
    }

}    