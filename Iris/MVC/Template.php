<?php

namespace Iris\MVC;

use Iris\Engine\Loader;

// See real class at bottom of file

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
 * The Template class manages .iview files, the translation to .phtml
 * and, optionaly the recording of a .phtml file
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Template implements \Iris\Translation\iTranslatable {

    use \Iris\Translation\tTranslatable;

    /**
     * The caching may desactivated, only in production (for test),
     * only in development or always
     */

    const CACHE_NEVER = 0;
    const CACHE_PRODUCTION = 1;
    const CACHE_DEVELOPMENT = 2;
    const CACHE_ALWAYS = 3;

    /**
     * Explicit way to require absolute filename for a template
     */
    const ABSOLUTE = \TRUE;

    /**
     * If true, the translation of .phtml is managed through files
     * 
     * @var boolean
     */
    private static $_CacheTemplate = \FALSE;

    /**
     * The view associated to the template (none in case of Quote views)
     * 
     * @var View
     */
    private $_view;
    /**
     * The required fileName without extension (may be null)
     * @var string
     */
    private $_initialScriptName;

    /**
     * The optional phtml file name
     * @var String 
     */
    private $_readScriptFileName;

    /**
     * If false, indicates that the _templateText is already translated to
     * phtml 
     * @var boolean
     */
    private $_toBeParsed = \TRUE;

    /**
     * The content of the template, as an array of lines
     * 
     * @var array(string)
     */
    private $_templateArray = '';

    /**
     * The template language as an array of array. Each 0 element beginning
     * with '/' is considered as a regular expression
     * 
     * @var array
     */
    private static $_Token = [
        // escaping {
        ['/%{%(.*)%}%/', '{$1}', \TRUE],
        // php tags
        ['{php}', '<?php '],
        ['{/php}', '?>'],
        // foreach with and without key
        ['/{foreach\((\w+),(\w+),(\w+)\)}/i', '<?php foreach($$1 as $$2=>$$3):?>'],
        ['/{foreach\((\w+),(\w+)\)}/i', '<?php foreach($$1 as $$2):?>'],
        ['{/foreach}', '<?php endforeach;?>'],
        // if then else
        ['/{if\((.+?)\)}/', '<?php if($1):?>'],
        ['{else}', '<?php else: ?>'],
        ['{/if}', '<?php endif;?>'],
        // switch equivalence (the dummy "c"ase '':" is required to avoid a PHP error.
        // A PHP closing tag followed by an opening tag is not accepted in this context).
        ['/{section\((.+?)\)}/', "<?php switch($$1): case '':?>"],
        ['{/section}', '<?php endswitch;?>'],
        ['/{block\((.+?)\)}/', '<?php case $1 :?>'],
        ['{/block}', '<?php break;?>'],
        // special variables
        //@todo better treat them in View
        ['{ITEM}', '<?=$this->ITEM?>'],
        ['{KEY}', '<?=$this->KEY?>'],
        ['{LOOPKEY}', '<?=$this->LOOPKEY?>'],
        ['{CURRENTLOOPKEY}', '<?=$this->CURRENTLOOPKEY?>'],
        ['{ALLDATA}', '<?=$this->ALLDATA?>'],
        // view and local variables
        ['/({)(\w+?)(})/', '<?=\$$2?>'],
        // long expressions (var + method)
        ['/({\()(.*?)(\)})/', '<?=\$$2?>'],
        // view helpers
        ['/({)(.*?)(})/', '<?=\$this->$2?>'],
    ];

    /**
     * 
     * @param String $iViewScriptName the script to parse (NULL in case of quoted views)
     * @param View $view the view associated to the template
     */
    function __construct($iViewScriptName, $view = \NULL, $absolute = \FALSE) {
        $this->_initialScriptName = $iViewScriptName;
        $this->_view = $view;
        if (!is_null($view)) {
            if ($absolute) {
                $this->_loadAbsoluteTemplate();
            }
            else {
                $this->_loadTemplate();
            }
        }
    }

    /**
     * An manual accessor to the template text (for quoted view)
     * 
     * @param string $text
     */
    function setText($text) {
        $this->_templateArray = $text;
    }

    /**
     * An absolute file name for script must exist exactly as specified
     * 
     * @throws \Iris\Exceptions\LoaderException
     */
    private function _loadAbsoluteTemplate() {
        $scriptFileName = IRIS_ROOT_PATH . $this->_initialScriptName;
        if (!file_exists("$scriptFileName.iview")) {
            throw new \Iris\Exceptions\LoaderException("Absolute script file $scriptFileName.iview doesn't seem to exist");
        }
        $this->_readTemplateAsArray($scriptFileName);
    }

    /**
     * An interface to the loader to get the content of the template from
     * an .iview file, or if possible, from an existing .phtml translation
     * 
     * @param boolean $absolute If true, reads a precise file whose name has been given
     * @throws \Iris\Exceptions\LoaderException
     */
    private function _loadTemplate() {
        $viewType = $this->_view->getViewType();
        $loader = Loader::GetInstance();
        $iviewScriptName = $this->_initialScriptName;
        $view = $this->_view;
        try {
            // the case where a scriptName has been explicitely required (renderNow)
            if (!is_null($iviewScriptName)) {
                $viewFile = $loader->loadView($iviewScriptName, "scripts", $view->getResponse());
            }
            else {
                $scriptName = $view->getViewScriptName();
                if ($scriptName == '') {
                    $scriptName = $view->getResponse()->getActionName();
                }
                $viewFile = $loader->loadView($scriptName, $view->viewDirectory(), $view->getResponse());
            }
        }
        catch (\Iris\Exceptions\LoaderException $l_ex) {
            throw new \Iris\Exceptions\LoaderException("Problem with $viewType " .
            $l_ex->getMessage(), NULL, $l_ex);
        }
        $scriptFileName = IRIS_ROOT_PATH . '/' . $viewFile;
        $this->_readTemplateAsArray($scriptFileName);
    }

    /**
     * Reads effectively a template (an iview or a phtml file if it exists)
     * 
     * @param string $scriptFileName
     */
    private function _readTemplateAsArray($scriptFileName) {
        $this->_view->SetLastScriptName($scriptFileName);
        $read = \FALSE;
        if (self::$_CacheTemplate and file_exists("$scriptFileName.phtml")) {
            $file = file("$scriptFileName.phtml");
            $this->_toBeParsed = \FALSE;
            $this->_readScriptFileName = "$scriptFileName.phtml";
            $read = \TRUE;
        }
        if (!$read) {
            $file = file("$scriptFileName.iview");
            $this->_readScriptFileName = "$scriptFileName.iview";
            $this->_toBeParsed = \TRUE;
        }
        $this->_templateArray = $file;
    }

    /**
     * Explores the array template and replace all template tags 
     * by php and or html code. Returns a string.
     *  
     * @param array $template
     * @return string 
     */
    public function renderTemplate() {
        if (!$this->_toBeParsed) {
            $phtml = implode("", $this->_templateArray);
        }
        else {
            $literal = FALSE;
            foreach ($this->_templateArray as &$line) {
                $line = preg_replace('$(.*){REM}.*{/REM}(.*)$i', '$1$2', $line);
                if (strpos($line, '{literal}') !== FALSE) {
                    $literal = \TRUE;
                    $line = str_replace('{literal}', '', $line);
                }
                elseif (strpos($line, '{/literal}') !== FALSE) {
                    $literal = \FALSE;
                    $line = str_replace('{/literal}', '', $line);
                }
                elseif (!$literal) {
                    foreach (self::$_Token as $tokens) {
                        if ($tokens[0][0] == '/') {
                            $modLine = preg_replace($tokens[0], $tokens[1], $line);
                        }
                        else {
                            $modLine = str_replace($tokens[0], $tokens[1], $line);
                        }
                        if ($line != $modLine) {
                            $line = $modLine;
                            if (isset($tokens[2]))
                                break;
                        }
                    }
                }
            }
            $phtml = implode("", $this->_templateArray);
            if (self::$_CacheTemplate and is_writable(dirname($this->_readScriptFileName))) {
                file_put_contents($this->_readScriptFileName, $phtml);
            }
        }
        return $phtml;
    }

    /**
     * Sets the catch template status for all the application
     * 
     * @param int $flag
     */
    public static function setCacheTemplate($flag = self::CACHE_PRODUCTION) {
        switch ($flag) {
            case self::CACHE_ALWAYS:
                $value = \TRUE;
                break;
            case self::CACHE_NEVER:
                $value = \FALSE;
                break;
            case self::CACHE_DEVELOPMENT:
                $value = \Iris\Engine\Mode::IsDevelopment();
                break;
            case self::CACHE_PRODUCTION:
                $value = \Iris\Engine\Mode::IsProduction();
                break;
        }
        self::$_CacheTemplate = $value;
    }

    /**
     * Accessor get for the actual read script file name (with extension)
     * 
     * @return string
     */
    public function getReadScriptFileName() {
        return $this->_readScriptFileName;
    }


}

