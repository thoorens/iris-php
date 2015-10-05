<?php

namespace modules\system\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of engine
 * 
 * @author jacques
 * @license not defined
 */
class engine extends _system {

    public function indexAction() {
        // this Title var is required by the default layout defined in _system
        $this->__Title = $this->callViewHelper('welcome', 1);
    }

    public function singletonAction() {
        /* @var $singleton1 \modules\system\classes\Daughter1 */
        $singleton1 = \modules\system\classes\Daughter1::GetInstance();

        /* @var $singleton2 \modules\system\classes\Daughter2 */
        $singleton2 = \modules\system\classes\Daughter2::GetInstance();
        $this->__name1 = $singleton1->getName();
        $this->__name2 = $singleton2->getName();

        /* @var $singletonCopy \modules\system\classes\Daughter1 */
        $singletonCopy = \modules\system\classes\Daughter1::GetInstance();
        $this->__copyName = $singletonCopy->getName();

        $singleton1->setName("Maria");
        $this->__changedName = $singleton1->getName();
        $this->__changedNameCopy = $singletonCopy->getName();
    }

    public function structuresAction(){
        $this->__value = 5;
        // Enable to find errors
        //\Iris\SysConfig\Settings::$CacheTemplate = \Iris\MVC\Template::CACHE_ALWAYS;
    }
}
