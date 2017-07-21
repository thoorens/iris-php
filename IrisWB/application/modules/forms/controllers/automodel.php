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

class automodel extends _forms {

    protected $_entityManager;

    protected function _init() {
        $this->_entityManager = 'default';
        $this->setViewScriptName('common/auto');
        $this->__base = '/forms/autoform/';
    }

    /* ------------------------------------------------------------------------------
     * Forms made using metadata
     * ------------------------------------------------------------------------------
     */

    /**
     * Tests and illustrates a form made from an model class (HTML)
     * 
     * @param string $dbManagerType Can force a change of entity manager type
     */
    public function HTMLAction($test = 1) {
        $factoryType = \Iris\Forms\_FormFactory::HTML;
        $this->_treatModel(Maker::MODE_NONE, $factoryType, $test);
    }

    /**
     * Tests and illustrates a form made from an model class  (Dojo)
     * 
     * @param string $dbManagerType Can force a change of entity manager type
     */
    public function DojoAction($test = 1) {
        $factoryType = \Iris\Forms\_FormFactory::DOJO;
        $this->_treatModel(Maker::MODE_NONE, $factoryType, $test);
    }

    /**
     * 
     * @param type $dbManagerType
     */
    public function HTML_iniAction() {
        $factoryType = \Iris\Forms\_FormFactory::HTML;
        $this->_treatModel(Maker::MODE_INI, $factoryType);
    }

    public function Dojo_iniAction() {
        $factoryType = \Iris\Forms\_FormFactory::DOJO;
        $this->_treatModel(Maker::MODE_INI, $factoryType);
    }

    public function HTML_HandAction() {
        $factoryType = \Iris\Forms\_FormFactory::HTML;
        $this->_treatModel(Maker::MODE_HANDMADE, $factoryType);
    }

    public function Dojo_HandAction() {
        $factoryType = \Iris\Forms\_FormFactory::DOJO;
        $this->_treatModel(Maker::MODE_HANDMADE, $factoryType);
    }

    /**
     * 
     * @param int $secondary
     * @param type $dbManagerType
     * @param type $factoryType
     */
    protected function _treatModel_orig($secondary, $factoryType) {
        $em = \Iris\DB\_EntityManager::EMByNumber(-2);
        $entityClass = \models\TInvoices::GetEntity($em);
        $maker = new \Iris\Forms\Makers\Entity($entityClass, $factoryType);
        if ($secondary == Maker::MODE_INI) {
            
        }
        else if ($secondary == Maker::MODE_HANDMADE) {
//            $maker->scanList([
//                "@!Text|N!Name|L!Nom du client:|T!Simple text",
//                "",
//                ""]);
        }
        //$maker->readList(["@!Text|N!Name|L!Nom du client:|T!Simple text"]);
        //die('MA');
        $this->__form = $maker->formRender();
    }

    /**
     * 
     * @param int $secondary
     * @param type $dbManagerType
     * @param type $factoryType
     */
    protected function _treatModel($secondary, $factoryType, $test) {
        $em = \Iris\DB\_EntityManager::EMByNumber(-2);
        if ($test === 1) {
            $entityClass = \models\TInvoices::GetEntity($em);
        }
        else {
            $entityClass = \models\TOrders::GetEntity($em);
        }
        $maker = new \Iris\Forms\Makers\Entity($entityClass, $factoryType);
        if ($secondary == Maker::MODE_INI) {
            
        }
        else if ($secondary == Maker::MODE_HANDMADE) {
//            $maker->scanList([
//                "@!Text|N!Name|L!Nom du client:|T!Simple text",
//                "",
//                ""]);
        }
        //$maker->readList(["@!Text|N!Name|L!Nom du client:|T!Simple text"]);
        //die('MA');
        $this->__form = $maker->formRender();
    }

    protected function _getSpecialList() {
        return ["@!Text|N!Name|L!Nom du client:|T!Simple text",
            "",
            ""];
    }

}
