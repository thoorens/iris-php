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
        $this->__finishedOrNot = '';
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
        $this->callViewHelper('dojo_tabContainer',"container")
                ->setDefault($default)
                ->setDim(250, 450)
                ->setPosition($position)
                ->setItems([
                    "first" => 'First tab',
                    "second" => 'Second tab',
                ]);
    }

    public function linkedTabsAction($default = 'first') {
        $this->setViewScriptName(\NULL);
        return $this->_tabs($default, \Dojo\views\helpers\TabContainer::TOP);
    }

    public function accordionsAction($default = 'first') {
        $this->__title = "Example of accordions";
        $this->callViewHelper('dojo_accordionContainer',"container")
                ->setDefault($default)
                ->setDim(250, 450)
                ->setItems([
                    "first" => 'First tab',
                    "second" => 'Second tab',
                ]);
    }

    public function splitAction($default = 'first') {
        $this->__title = "Example of split screen";
        $this->callViewHelper('dojo_splitContainer',"container")
                ->setDefault($default)
                ->setDim(250, 450)
                ->setItems([
                    "first" => 'First tab',
                    "second" => 'Second tab',
                ]);
    }

    public function stackAction($default = 'first') {
        $this->__title = "Example of stack";
        $this->callViewHelper('dojo_stackContainer',"container")
                ->setDefault($default)
                ->setDim(250, 450)
                ->setPosition(\Dojo\views\helpers\StackContainer::BOTTOM)
                ->setItems([
                    "first" => 'First tab',
                    "second" => 'Second tab',
                ]);
        $this->__finishedOrNot = $this->callViewHelper('underConstruction');
    }

    public function borderAction() {
        // reset to default script
        $this->setViewScriptName('');
        $this->__title = "Example of border";
        $this->callViewHelper('dojo_borderContainer',"container")
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
        $this->callViewHelper('dojo_borderContainer',"container")
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

    public function titleAction() {
        // reset to default script
        $this->setViewScriptName('');
        $this->__title = "Example of title pane";
        $this->callViewHelper('dojo_titlePane','titlepane');
        $this->callViewHelper('dojo_titlePane','titlepane2');
    }

    /**
     * Example of programatical use of a container (tab)
     */
    public function tabProgAction() {
        $this->setViewScriptName('');
        // using a partial, you can produce whatever output you want.
        $text1 = $this->callViewHelper('partial', 'random');
        // a simple helper can be directly called
        $text2 = $this->callViewHelper('loremIpsum',[101, 102, 103, 104]);
        // template text is rendered literally
        $text3 = '<h4>No evaluation</h4>{loremIpsum([10, 20, 30, 40]}';
        // use helper quote() to have quoted template
        $text4 = $this->callViewHelper('quote','<h4>Good evaluation</h4>{loremIpsum([10, 20, 30, 40])}');
        $this->__data = [
            "Tab 1 " => $text1, 
            "Tab 2" => $text2, 
            'Tab 3' => $text3,
            'Tab 4' => $text4];
    }

}
