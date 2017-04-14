<?php

namespace Iris\Forms\Makers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * Description of HandMade
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class IniFile extends \Iris\Forms\_FormMaker {

    protected $_fileName;

    /**
     * 
     * @param type $formName
     * @param type $factoryType
     * @return \static
     */
    protected static function _GetMaker($formName, $factoryType) {
        $maker = new static();
        $maker->setFactory($factoryType);
        $factory = $maker->getFormFactory();
        $maker->setForm($factory->createForm($formName));
        return $maker;
    }

    public function setFile($fileName) {
        $parser = \Iris\SysConfig\IniParser::ParserBuilder('ini');
        $configs = $parser->processFile($fileName, \FALSE, \Iris\SysConfig\_Parser::NO_INHERITANCE);
        /* @var $config0 \Iris\SysConfig\Config */
        $config0 = array_shift($configs);
        $formName = $config0->formname;
        if ($formName !== \NULL) {
            $this->_form->changeFormName($formName);
        }
        $this->_elements = $configs;
    }

    public function formRender() {
        return parent::formRender();
    }

    protected function _insertElements() {
        /* @var $element \Iris\SysConfig\Config */
        foreach ($this->_elements as $name => $config) {
            echo($name) . '*';
            switch ($config->type) {
                case 'hidden':
                    $element = $this->_formFactory->createHidden($name);
                    break;

                default:
                    $element = $this->_formFactory->createText($name);
            }
            $this->_form->addElement($element);
        }
        i_d($this->_form->getComponent());
        die('OK');
        $this->_form->addElement($this->getSubmitButton());
    }

}
