<?php

namespace modules\forms\controllers;

use Iris\Forms\_FormMaker as Maker;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * This is part of the WorkBench fragment
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * This is a test for Autoform class
 */
class autohand extends _forms {

    const TYPE = 0;
    const LABEL = 1;

    protected $_entityManager;

    protected function _init() {
        $this->setViewScriptName('common/auto');
        //\Iris\SysConfig\Settings::$DefaultFormClass = '\\Dojo\\Forms\\FormFactory';
        $this->__base = '/forms/autoform/';
    }

    /* ------------------------------------------------------------------------------
     * Forms made by hand
     * ------------------------------------------------------------------------------
     */

    /**
     * Tests and illustrates a form made by hand (HTML)
     * 
     * @param string $dbManagerType Can force a change of entity manager type
     */
    public function HTMLAction() {
        $factoryType = \Iris\Forms\_FormFactory::HTML;
        $form = $this->_treatHand(Maker::MODE_NONE, $factoryType);
        $this->__form = $form->render();
    }

    public function HTML_ModelAction() {
        $factoryType = \Iris\Forms\_FormFactory::HTML;
        $form = $this->_treatHand(Maker::MODE_ENTITY, $factoryType);
        $this->__form = $form->render();
    }

    public function HTML_iniAction() {
        $factoryType = \Iris\Forms\_FormFactory::HTML;
        $form = $this->_treatHand(Maker::MODE_INI, $factoryType);
        $this->__form = $form->render();
    }

    /**
     * Tests and illustrates a form made by hand (Dojo)
     * 
     * @param string $dbManagerType Can force a change of entity manager type
     */
    public function DojoAction() {
        $factoryType = \Iris\Forms\_FormFactory::DOJO;
        $form = $this->_treatHand(Maker::MODE_NONE, $factoryType);
        $this->__form = $form->render();
    }

    public function Dojo_ModelAction() {
        $factoryType = \Iris\Forms\_FormFactory::DOJO;
        $form = $this->_treatHand(Maker::MODE_ENTITY, $factoryType);
        $this->__form = $form->render();
    }

    public function Dojo_iniAction() {
        $factoryType = \Iris\Forms\_FormFactory::DOJO;
        $form = $this->_treatHand(Maker::MODE_INI, $factoryType);
        $this->__form = $form->render();
    }

    /**
     * 
     * @param type $dbManagerType
     * @param type $factoryType
     */
    protected function _treatHand($secondary, $factoryType) {
        $list = [
            "@!Text|N!Name|L!Nom du client:|T!Simple text",
            "@!Password|N!Password|L!Mot de passe:|T!",
            "@!Hidden|N!Address|T!Zone cachée",
            "@!Date|N!Date|L!Date de naissance|T!Date de naissance du client",
            "@!Time|N!Time|L!Heure du rendez-vous:|T!Heure précise du rendez-vous",
            "@!DateTime|N!DT|L!Jour de l'inscription:|T!Date et heure",
            "@!DateTimeLocal|N!NDTL|L!Jour de l'inscription (2):|T!Variante locale",
            "@!Month|N!Month|L!Mois de la réunion:|T!Variante mois",
            "@!Email|N!Email|L!Adresse de courriel:|T!Adresse de courriel complète",
            "@!Url|N!URL|L!Site web:|T!URL légale",
            "@!Color|N!Color|L!Couleur du logo:|T!Couleur utilisée pour le fond du logo",
            "@!Number|N!Number|L!Délais:|T!Délai de livraison prévu",
            "@!Tel|N!Tel|L!Téléphone du client:|T!Numéro de téléphone du client",
            "@!Range|N!Range|L!Intervalle:|T!Intervalle prévu pour les réunions",
        ];
        $maker = new \Iris\Forms\Makers\HandMade($list, $factoryType);
        if ($secondary == Maker::MODE_ENTITY) {
            
        }
        elseif ($secondary == Maker::MODE_INI) {
            
        }
        $maker->setSubmitText('Valider');
        $form = $maker->getForm(\FALSE);
        $form->setLayout(new \Iris\Forms\TabLayout());
        //$factory = $maker->getFormFactory();
        return $form;
    }

    /*
      /forms/autohand/HTML
      /forms/autohand/Dojo
      /forms/autohand/HTML_Ini
      /forms/autohand/Dojo_Ini
      /forms/autohand/HTML_Model
      /forms/autohand/Dojo_Model

      /forms/autoini/HTML
      /forms/autoini/Dojo
      /forms/autoini/HTML_Hand
      /forms/autoini/Dojo_Hand
      /forms/autoini/HTML_Model
      /forms/autoini/Dojo_Model

      /forms/automodel/HTML
      /forms/automodel/Dojo
      /forms/automodel/HTML_Ini
      /forms/automodel/Dojo_Ini
      /forms/automodel/HTML_Hand
      /forms/automodel/Dojo_Hand
      INSERT INTO "main"."sequences" ("id","URL","Description","Error","EN","FR","section_id","Label","Md5","Params","DE","IT","NL","ES") VALUES (41110,?1,?2,?3,?4,?5,?6,?7,?8,?9,?10,?11,?12,?13)
      Parameters:
      param 1 (text): /forms/autoini/Dojo
      param 2 (text): FormMaker (form from an entity)
      param 3 (text): False
      param 4 (null): NULL
      param 5 (text): <h2>FormMaker (form from an entity)</h2>
      Un formulaire dérivé automatiquement d'une entité (HTML).
      param 6 (integer): 41
      param 7 (null): NULL
      param 8 (null): NULL
      param 9 (null): NULL
      param 10 (null): NULL
      param 11 (null): NULL
      param 12 (null): NULL
      param 13 (null): NULL
     * 
     * 




     * 

     */
}
