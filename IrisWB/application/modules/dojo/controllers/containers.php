<?php

namespace modules\dojo\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of animation
 * 
 * @author jacques
 * @license not defined
 */
class containers extends _dojo {

    protected function _init() {
        $this->setViewScriptName('all');
    }

    public function tabsAction($default = 'first') {
        return $this->_tabs($default, \Dojo\views\helpers\TabContainer::TOP);
    }

    /**
     * An alias for tabs action
     * 
     * @param type $default
     * @return type
     */
    public function tabsTopAction($default = 'first') {
        return $this->_tabs($default, \Dojo\views\helpers\TabContainer::TOP);
    }

    public function tabsLeftAction($default = 'first') {
        return $this->_tabs($default, \Dojo\views\helpers\TabContainer::LEFT);
    }

    public function tabsRightAction($default = 'first') {
        return $this->_tabs($default, \Dojo\views\helpers\TabContainer::RIGHT);
    }

    public function tabsBottomAction($default = 'first') {
        return $this->_tabs($default, \Dojo\views\helpers\TabContainer::BOTTOM);
    }
    
    private function _tabs($default, $position) {
        $this->_view->dojo_tabContainer("containers")
                ->setDefault($default)
                ->setDim(250, 450)
                ->setPosition($position)
                ->setItems([
                    "first" => 'First tab',
                    "second" => 'Second tab',
                ]);
    }

    public function linkedTabsAction($default = 'first'){
        $this->setViewScriptName(\NULL);
        return $this->_tabs($default, \Dojo\views\helpers\TabContainer::TOP);
    }
    
    public function accordionsAction($default = 'first') {
        $this->__title = "Example of accordions";
        $this->_view->dojo_accordionContainer("containers")
                ->setDefault($default)
                ->setDim(250, 450)
                ->setItems([
                    "first" => 'First tab',
                    "second" => 'Second tab',
                ]);
    }

    public function splitAction($default = 'first') {
        $this->__title = "Example of split screen";
        $this->_view->dojo_splitContainer("containers")
                ->setDefault($default)
                ->setDim(250, 450)
                ->setItems([
                    "first" => 'First tab',
                    "second" => 'Second tab',
                ]);
    }

    public function stackAction($default = 'first') {
        $this->__title = "Example of stack";
        $this->_view->dojo_stackContainer("containers")
                ->setDefault($default)
                ->setDim(250, 450)
                ->setPosition(\Dojo\views\helpers\StackContainer::BOTTOM)
                ->setItems([
                    "first" => 'First tab',
                    "second" => 'Second tab',
                ]);
    }

    
    public function borderAction() {
        // reset to default script
        $this->setViewScriptName('');
        $this->__title = "Example of border";
        $this->_view->dojo_borderContainer("containers")
                ->setDim(250, 450)
                ->setLayoutMode(\Dojo\views\helpers\BorderContainer::HEADLINE)
                ->setItems([
                    \Dojo\views\helpers\BorderContainer::TOP => 'First tab',
                    \Dojo\views\helpers\BorderContainer::BOTTOM => 'Second tab',
                    \Dojo\views\helpers\BorderContainer::LEFT => 'Third tab',
                    \Dojo\views\helpers\BorderContainer::RIGHT => 'Fourth tab',
                    \Dojo\views\helpers\BorderContainer::CENTER => 'Fith tab',
                ]);
    }

    public function borderSideAction() {
        $this->setViewScriptName('border');
        $this->__title = "Example of border";
        $this->_view->dojo_borderContainer("containers")
                ->setDim(250, 450)
                ->setLayoutMode(\Dojo\views\helpers\BorderContainer::SIDEBAR)
                ->setItems([
                    \Dojo\views\helpers\BorderContainer::TOP => 'First tab',
                    \Dojo\views\helpers\BorderContainer::BOTTOM => 'Second tab',
                    \Dojo\views\helpers\BorderContainer::LEFT => 'Third tab',
                    \Dojo\views\helpers\BorderContainer::RIGHT => 'Fourth tab',
                    \Dojo\views\helpers\BorderContainer::CENTER => 'Fith tab',
                ]);
    }
    public function titleAction(){
        // reset to default script
        $this->setViewScriptName('');
        $this->__title = "Example of title pane";
        $this->_view->dojo_titlePane('titlepane');
        $this->_view->dojo_titlePane('titlepane2');
    }
}
