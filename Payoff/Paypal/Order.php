<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Payoff\Paypal;

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
 * @copyright 2011-2013 Jacques THOORENS
 */

/**
 * Description of PayOff\Paypal\Order
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Order extends \Payoff\_Order {

    use \Payoff\tTranslatable;

    const PAYPAL_BUTTON = 'palpal';
    const LOCAL_BUTTON = 1;

    /**
     * ????
     * @var string
     */
    private $_cmd = \NULL;

    /**
     * ????
     * @var string
     */
    private $_bn = \NULL;

    /**
     *
     * @var \Iris\Forms\_Form
     */
    private $_form;

    public function __construct() {
        $this->setCmd("_xclick");
        $this->setBn("PP-BuyNowBF");
    }

    /* =====================================================================
     * Renderers
     * =====================================================================
     */

    public function _renderField($name, $value) {
        if (is_null($value)) {
            return '';
        }
        //$formFactory = $this->_form->getFormFactory();
        $this->_form->createHidden($name)
                ->setValue($value)
                ->addTo($this->_form);
        return '';
    }

    public function render() {
        $formFactory = \Iris\Forms\_FormFactory::GetDefaultFormFactory();
        $form = $formFactory->createForm('formOrder');
        $this->_form = $form;
        $site = $this->_getSite();
        $form->setAction("https://$site/cgi-bin/webscr");
        $form->addAttribute("target", "_blank");
        // Paypal specific
        $this->_renderBn();
        $this->_renderCmd();
        parent::render();
        return $form->render();
    }

    /* =========================================================================
     * P A Y P A L    S P E C I F I C
     * =========================================================================
     */

    protected function _renderCmd() {
        return $this->_renderField('cmd', $this->_cmd);
    }

    protected function _renderBn() {
        return $this->_renderField('bn', $this->_bn);
    }

    public function setCmd($cmd) {
        $this->_cmd = $cmd;
        return $this;
    }

    public function setBn($bn) {
        $this->_bn = $bn;
        return $this;
    }

    protected function _renderSubmit() {
        // Verify if submit has been defined
        parent::_renderSubmit();
        switch ($this->_submit) {
            case self::LOCAL_BUTTON:
                $element = $this->_form->createSubmit('submit')
                        ->setValue($this->_('Buy now'));
                break;
            case self::PAYPAL_BUTTON;
                $title = $this->_('PayPal - The safer, easier way to pay online!');
                $locale = 'fr_FR';
                $element = $this->_form->createImage('submit')
                        ->setSrc("https://www.paypalobjects.com/$locale/i/btn/btn_buynowCC_LG.gif")
                        ->setAlt('A  link to Papayl')
                        ->setTitle($title);
                break;
        }
        $element->addTo($this->_form);
    }

    protected function _getSite() {
        if (\Iris\Engine\Mode::IsProduction() and !$this->_demo) {
            $site = "www.paypal.com";
        }
        else {
            $site = "www.sandbox.paypal.com";
        }
        return $site;
    }

   
    

    

}

?>
