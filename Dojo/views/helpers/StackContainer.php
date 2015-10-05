<?php

namespace Dojo\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This helper permits to use stack containers of Dojo. If javascript
 * is not available on the client, it simulate the tab with buttons and interaction
 * with the server. Another option is to display all the items, with <h5> title in front
 * of them.
 *

 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class StackContainer extends _Container {

    protected static $_Type = 'StackContainer';
    
    

    /**
     * 
     * @return StackContainer
     */
    public static function GetInstance() {
        return parent::GetInstance();
    }
    
    /**
     * 
     * @param type $position
     * @return string
     * @todo Terminate
     */
    protected function _renderController($position) {
        // only if position has been defined and at the necessary place
        if ($this->_position === $position) {
            $html = $this->controller();
        }
        else {
            $html = '';
        }
        return $html;
    }

   /**
     * 
     * @param type $position
     * @return \Dojo\views\helpers\StackContainer
     */
    public function setPosition($position) {
        $this->_position = $position;
        return $this;
    } 
    
    

    /**
     * 
     * @param type $option
     * @return \Dojo\views\helpers\StackContainer
     */
    public function doLayout($option) {
        $value = $option ? 'true' : 'false';
        $this->_specials[] = sprintf("doLayout:'%s' ", $value);
        return $this;
    }

    /**
     * 
     * @param type $counter
     * (["dojo/dom","dojo/topic","dojo/dom-construct","dojo/domReady!"],function(dom,topic,domConst){ 
     */
    public function listenTo($counter) {
        $name = 'stackgroup__' . $this->_position;
        \Dojo\Engine\Bubble::GetBubble($name)
                ->addModule('dojo/dom','dom')
                ->addModule('dojo/topic','topic')
                ->addModule('dojo/dom-construct','domConst')
                ->addModule('dojo/domReady')
                ->defFunction(<<<JS

   /* This is my code */
topic.subscribe('MESS',function(time,max){
      alert('Bonjour');                  
      if(time%100==0)domConst.place('<span id="counter1">'+time/100+'</span>', 'counter1', 'replace');
    ;});
JS
                );
        return $this;
    }

}
