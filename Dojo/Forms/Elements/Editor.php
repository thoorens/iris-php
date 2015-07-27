<?php

namespace Dojo\Forms\Elements;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * An editor widget adding html support to areaelement (uses Dojo)
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Editor extends \Iris\Forms\Elements\AreaElement {
    use \Dojo\Forms\tDojoPlugins; /* includes tDojoDijit */
    
    protected $_dojoManager;
    protected $_hiddenCompanion;
    /**
     *
     * @var \Dojo\Engine\Bubble
     */
    protected $_bubble;

    public function __construct($name, $options = array()) {
        $editorName = $name . "_editor";
        parent::__construct($editorName, $options);
        $this->_type = 'div';
        $hiddenCompanion = new \Iris\Forms\Elements\InputElement($name, 'hidden');
        $this->_hiddenCompanion = $hiddenCompanion;
        
        $bubble = \Dojo\Engine\Bubble::GetBubble($name);
        $bubble->addModule("dijit/Editor",'editor')
                ->addModule("dijit/form/TextBox")
                ->addModule('dojo/domReady!')
                ->addModule('dijit/_editor/plugins/AlwaysShowToolbar');
        $bubble->defFunction(<<<JSEditor
            dojo.addOnLoad(function() {
            var editor = dijit.byId("$editorName");
            dojo.connect(editor, "onChange", this, function(event) {
            dojo.byId("$name").value = editor.get("value");
            });
            }); 
JSEditor
        );
        $this->setDijitType("dijit.Editor")
                ->setInheritWidth(TRUE)
                ->setHeight('150px');
        $this->_bubble = $bubble;
    }

    public function addTo(\Iris\Forms\iFormContainer $container) {
        //iris_debug($this->_hiddenCompanion);
        $this->_hiddenCompanion->addTo($container);
        return parent::addTo($container);
    }

    public function getValue() {
        return trim(str_replace('&quot;','"',$this->_hiddenCompanion->getValue()));
    }

    // setValue() is never used (why??)

   


    public function toolBar($string) {
        foreach (str_split($string) as $letter) {
            switch ($letter) {
                case 'A':
                    $this->withSelectAll();
                    break;
                case 'B':
                    $this->withBold();
                    break;
                case 'C':
                    $this->withCopy();
                    break;
                case 'c':
                    $this->withJustifyCenter();
                    break;
                case 'D':
                    $this->withDelete();
                    break;
                case 'f':
                    $this->withJustifyFull();
                    break;
                case 'I':
                    $this->withItalic();
                    break;
                case 'l':
                    $this->withJustifyLeft();
                    break;
                case 'o':
                    $this->withInsertOrderedList();
                    break;
                case 'P':
                    $this->withPaste();
                    break;
                case 'r':
                    $this->withJustifyRight();
                    break;
                case 'R':
                    $this->withRedo();
                    break;
                case 'S':
                    $this->withStrikethrough();
                    break;
                case 'U':
                    $this->withUndo();
                    break;
                case 'u':
                    $this->withInsertUnorderedList();
                    break;
                case 'X':
                    $this->withCut();
                    break;
                case '_':
                    $this->withUnderline();
                    break;
                case '|':
                    $this->withSeparator();
                    break;
                case '>':
                    $this->withIndent();
                    break;
                case '<':
                    $this->withOutdent();
                    break;
                case "'":
                    $this->withSuperscript();
                    break;
                case ',':
                    $this->withSubscript();
                    break;
                case '-':
                    $this->withInsertHorizontalRule();
            }
        }
        return $this;
    }

    public function StandardToolBar() {
        $this->toolBar('UR|XCVP|BI_S|ou><|lrcf|');
        return $this;
    }

    public function __call($name, $arguments) {
        if (strpos($name, 'with') === 0) {
            $function = substr($name, 4);
            $function[0] = strtolower($function[0]);
            switch ($function) {
                case 'hiliteColor':
                case 'foreColor':
                    $this->_bubble->addModule('dijit/_editor/plugins/TextColor');
                    $this->_addPlugin($function);
                    break;
                case "createLink":
                case "insertImage":
                    $this->_bubble->addModule("dijit/_editor/plugins/LinkDialog");
                    $this->_addPlugin($function);
                    break;
                case "toggleDir":
                    $this->_bubble->addModule("dijit/_editor/plugins/ToggleDir");
                    $this->_addPlugin($function);
                    break;
                case "fullscreen":
                    $this->_bubble->addModule("dijit/_editor/plugins/FullScreen");
                    $this->_addPlugin($function);
                    break;
                case "print":
                    $this->_bubble->addModule("dijit/_editor/plugins/Print");
                    $this->_addPlugin($function);
                    break;
                case "viewsource":
                    $this->_bubble->addModule("dijit/_editor/plugins/ViewSource");
                    $this->_addPlugin($function);
                    break;
                case "newpage":
                    $this->_bubble->addModule("dijit/_editor/plugins/NewPage");
                    $this->_addPlugin($function);
                    break;
                case 'fontName':
                case 'fontSize':
                case 'formatBlock':
                    //$this->_bubble->addModule('editor_font');
                    $this->_bubble->addModule('dijit/_editor/plugins/FontChoice');
                    $this->_addExtraPlugin($function, "extraPlugins:['fontName', 'fontSize', 'formatBlock']");
                    break;

                case 'createLink':
                    break;
                case 'separator':
                    $this->_addPlugin('|');
                    break;
                default:
                    $this->_addPlugin($function);
                    break;
            }
            return $this;
        }
        else {
            return parent::__call($name, $arguments);
        }
    }

}


