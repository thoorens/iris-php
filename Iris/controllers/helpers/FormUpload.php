<?php

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
 * A helper that creates an upload form
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class FormUpload extends _ControllerHelper implements \Iris\Translation\iTranslatable {

    //PHP 5.4 use \Iris\Translation\tTranslatable;
    
    /**
     *
     * @return \Iris\Form\Form 
     */
    public function help() {
        $ff = \Iris\Forms\_FormFactory::GetDefaultFormFactory();
        $form = $ff->createForm('Loader');
        // NO UPLOAD WITHOUT THIS ATTRIBUTE !!
        $form->addAttribute('enctype',"multipart/form-data");

        new \Iris\Forms\PlaceHolder('_before_', $form);

        $ff->createFile('File',array('maxfilesize'=>'900000'))
                ->setLabel($this->_('File to upload:',TRUE))
                ->addTo($form)
                ->addValidator('Required');

        new \Iris\Forms\PlaceHolder('_after_', $form);

        
        $ff->createSubmit('Submit')
                ->setValue($this->_('Upload',TRUE))
                ->addTo($form);
        return $form;
    }

    /* Beginning of trait code */
    
    /**
     * Translates a message
     * @param string $message
     * @param boolean $system
     * @return string 
     */
    public function _($message, $system=\FALSE) {
        if ($system) {
            $translator = \Iris\Translation\SystemTranslator::GetInstance();
            return $translator->translate($message);
        }
        $translator = $this->getTranslator();
        return $translator->translate($message);
    }

    /**
     *
     * @staticvar \Iris\Translation\_Translator $translator
     * @return \Iris\Translation\_Translator
     */
    public function getTranslator() {
        static $translator = NULL;
        if (is_null($translator)) {
            $translator = \Iris\Translation\_Translator::GetCurrentTranslator();
        }
        return $translator;
    }
    
    /* end of trait code */

}

