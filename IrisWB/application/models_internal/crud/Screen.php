<?php

namespace models_internal\crud;

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
 */

/**
 * 
 * Test of basic crud operations
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Screen extends \Iris\DB\DataBrowser\_Crud {

    private $_section;

    public function __construct($param = NULL) {
        // a crud controller sends itself a first parameter
        /* @var $controller \Iris\MVC\_Controller */
        $controller = $param[0];
        $param = $controller->getParameters()[0];
        if($controller->getActionName() == "create_screen"){
            $section = $param;
        }
        else{
            $section = (int)($param/1000);
        }
        parent::__construct();
        $entity = \models_internal\TSequence::GetEntity();
        $this->setEntity($entity);
        $this->setActions("erreur", "index/" . $section);
        $this->setForm($this->_createForm());
        $this->_section = $section;
    }

    protected function _preCreate($formData, &$object) {
        
    }

    protected function _preDelete(&$object) {
        $this->_setSection($object);
    }

    protected function _preDisplay($type, &$data) {
        //$this->_form->getComponent('section_id')->setValue($this->_section);
    }

    protected function _preUpdate($formData, &$object) {
        $this->_setSection($object);
    }

    private function _setSection($object) {
        //$section = $object->section_id;
    }

    /*  id
      URL
      Description
      Error
      EN
      FR
      Label
      Md5
      section_id */

    private function _createForm() {
        $formFactory = new \Dojo\Forms\FormFactory();
        $form = $formFactory->createForm('screen');

        $form->setLayout(new \Iris\Forms\TabLayout());

        // id
        $formFactory->createText('id')
                ->addTo($form)
                ->setSize(5)
                ->setLabel('Identifier:');

        $formFactory->createText('URL')
                ->addTo($form)
                ->setSize(25)
                ->setLabel('URL of the screen:');

        $formFactory->createText('Description')
                ->addTo($form)
                ->setSize(25)
                ->setLabel('Main title (in english):');

        $categories = \models_internal\TSections::getListById();
        $formFactory->createSelect('section_id')
                ->addTo($form)
                ->addOptions($categories)
                ->setLabel('Category:');

        $formFactory->createEditor('FR')
                ->addTo($form)
                ->setColor('blue')
                ->withForeColor()
                ->withHiliteColor()
                ->StandardToolBar()
                ->setLabel('French description:');

        $formFactory->createSubmit('Submit')
                ->addTo($form)
                ->setValue('Validate');
        return $form;
    }

}
