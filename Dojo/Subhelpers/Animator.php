<?php

namespace Dojo\Subhelpers;

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
 *
 */

/**
 * This subhelper is used by some Dojo helpers that need some complex javascript
 * code
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Animator extends \Iris\Subhelpers\_Subhelper {


use \Iris\Engine\tSingleton;

    /**
     * The animationManager part of the code
     * 
     * @var CodeContainer
     */
    private $_animationManager;

    /**
     * The prefix first part of the code
     * 
     * @var CodeContainer
     */
    private $_prefixCode;

    /**
     *
     * @var CodeContainer
     */
    private $_codeContainer;

    /**
     * Initializes a code container <ul>
     * <li>header (here)
     * <li>body <ol>
     *      <li> prefixes ( opacity)
     *      <li> animationManager
     *      <li> init code (here)
     * </ol>
     * <li>tail
     * </ul>
     * @return \Dojo\Subhelpers\CodeContainer
     * @todo The head will contain all possible dojo modules. Find a way to use only the required ones
     */
    public function __construct() {
        $codeContainer = new \Dojo\Subhelpers\CodeContainer();
        $header = <<< SCRIPT
    require(["dojo/dom", "dojo/_base/fx", "dojo/on", "dojo/dom-style","dojo/fx", "dojo/topic", 
        "dojo/window", "dojo/domReady!"],
    function(dom, fx, on, style, coreFx, topic, win){
   
SCRIPT;
        $codeContainer->setHeader($header);
        $prefixes = new CodeContainer();
        $codeContainer->addPieceOfCode(0, $prefixes);
        $animationManager = new CodeContainer();
        $codeContainer->addPieceOfCode(1, $animationManager);
        $init = new CodeContainer();
        $codeContainer->addPieceOfCode(2, $init);
        $init->addPieceOfCode('init', <<< SCRIPT

        if(args.button == null){
            setTimeout(animationManager,args.delay); 
        }
        else{
            on(dom.byId(args.button), "click", animationManager);
        }
SCRIPT
        );
        $codeContainer->setTail('    })');
        $animationManager->setHeader('        function animationManager(){');
        $animationManager->setTail('        }');
        $turn = new CodeContainer();
        $animationManager->addPieceOfCode('turn', $turn);
        $this->_animationManager = $animationManager;
        $this->_prefixCode = $prefixes;
        $this->_codeContainer = $codeContainer;
    }


    /**
     * Adds a piece of code to animation manager (central part of the animation mechanism
     * @param string $name
     * @param string $code
     */
    public function addToAnimationManager($name, $code) {
        $this->_animationManager->addPieceOfCode($name, $code);
    }

    /**
     * Returns the central par of the animation mechanism
     * 
     * @return CodeContainer
     */
    public function getAnimationManager() {
        return $this->_animationManager;
    }

    /**
     * Returns the prefix part of the animation mechanism (responsible of initialization e.g. opacity)
     * @return CodeContainer
     */
    public function getPrefixCode() {
        return $this->_prefixCode;
    }

    /**
     * Returns the code container used by Animator helper
     * 
     * @return CodeContainer
     */
    public function getCodeContainer() {
        return $this->_codeContainer;
    }

    

    

}

