<?php

namespace Dojo\Forms\Elements;

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
 * @copyright 2012 Jacques THOORENS
 */

/**
 * An editor widget adding html support to areaelement (uses Dojo)
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Editor extends \Iris\Forms\Elements\AreaElement {

    protected $_dojoManager;
    protected $_plugins = array();
    protected $_extraPlugins = array();
    protected $_hiddenCompanion;

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
//        $dojoManager = \Dojo\Manager::GetInstance();
//        $this->_dojoManager = $dojoManager;
//        $dojoManager->addRequisite('dojo_editor',[
//        "dijit/Editor",
//        'dojo/domReady!',
//        'dijit/_editor/plugins/AlwaysShowToolbar']);
//        //$dojoManager->addInitCode('editor_hidden', <<<JSEditor
        $bubble->defFunction(<<<JSEditor
            dojo.addOnLoad(function() {
            var editor = dijit.byId("$editorName");
            dojo.connect(editor, "onChange", this, function(event) {
            dojo.byId("$name").value = editor.get("value");
            });
            }); 
JSEditor
        );
        $this->setDojoType("dijit.Editor")
                ->setInheritWidth(TRUE)
                ->setHeight('150px');
    }

    public function addTo(\Iris\Forms\iFormContainer $container) {
        //iris_debug($this->_hiddenCompanion);
        $this->_hiddenCompanion->addTo($container);
        return parent::addTo($container);
    }

    public function getValue() {
        return trim($this->_hiddenCompanion->getValue());
    }

    public function setValue($value) {
        $this->_value = trim($value);
        return $this;
    }

    public function render($layout = \NULL) {
        if (count($this->_plugins)) {
            $plugins = implode("','", $this->_plugins);
            $this->setPlugins("['$plugins']");
        }
        if (count($this->_extraPlugins)) {
            foreach ($this->_extraPlugins as $command => $extra) {
                $extraPlugins[] = sprintf("{name:'%s',command:'%s'}", $extra, $command);
            }
            $extraPlugins = implode(',', $extraPlugins);
            $this->setExtraPlugins("[$extraPlugins]");
        }
        $html = $this->_hiddenCompanion->render($this->getLayout());
        return parent::render($layout);
    }

    public function _addPlugin($plugin) {
        $this->_plugins[] = $plugin;
    }

    public function _addExtraPlugin($command, $plugin) {
        $this->_extraPlugins[$command] = $plugin;
    }

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

    public function StandartToolBar() {
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
                    $this->_dojoManager->addRequisite("editor_color", "dijit._editor.plugins.TextColor");
                    $this->_addPlugin($function);
                    break;
                case "createLink":
                case "insertImage":
                    $this->_dojoManager->addRequisite("editor_link", 'dijit._editor.plugins.LinkDialog');
                    $this->_addPlugin($function);
                    break;
                case "toggleDir":
                    $this->_dojoManager->addRequisite("editor_link", 'dijit._editor.plugins.ToggleDir');
                    $this->_addPlugin($function);
                    break;
                case "fullscreen":
                    $this->_dojoManager->addRequisite("editor_full", 'dijit._editor.plugins.FullScreen');
                    $this->_addPlugin($function);
                    break;
                case "print":
                    $this->_dojoManager->addRequisite("editor_print", 'dijit._editor.plugins.Print');
                    $this->_addPlugin($function);
                    break;
                case "viewsource":
                    $this->_dojoManager->addRequisite("editor_source", 'dijit._editor.plugins.ViewSource');
                    $this->_addPlugin($function);
                    break;
                case "newpage":
                    $this->_dojoManager->addRequisite("editor_new", 'dijit._editor.plugins.NewPage');
                    $this->_addPlugin($function);
                    break;
                case 'fontName':
                case 'fontSize':
                case 'formatBlock':
                    $this->_dojoManager->addRequisite("editor_font", 'dijit._editor.plugins.FontChoice');
                    $this->_addExtraPlugin($function, 'dijit._editor.plugins.FontChoice');
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


