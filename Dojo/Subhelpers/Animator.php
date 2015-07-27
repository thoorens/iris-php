<?php

namespace Dojo\Subhelpers;
use Dojo\Engine\CodeContainer;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
     *
     * @var \Dojo\Engine\Bubble
     */
    private $_header;
    
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
     * @return \Dojo\Engine\CodeContainer
     * @todo The head will contain all possible dojo modules. Find a way to use only the required ones
     */
    public function __construct() {
        $codeContainer = new \Dojo\Engine\CodeContainer();
        $header = \Dojo\Engine\Bubble::GetBubble('Animation code');
        $header->addModule('dojo/domReady!');
        $this->_header = $header;
//        $header = <<< SCRIPT
//    require(["dojo/dom", "dojo/_base/fx", "dojo/on", "dojo/dom-style","dojo/fx", "dojo/topic", 
//        "dojo/window", "dojo/domReady!"],
//    function(dom, fx, on, style, coreFx, topic, win){
//   
//SCRIPT;
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

    public function addModule($moduleName, $linkedVar){
        $this->_header->addModule($moduleName, $linkedVar);
    }

    

}

