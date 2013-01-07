<?php

namespace Iris\MVC;

use Iris\Engine as ie;

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
class Template {

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
    private $_iViewScriptName;

    /**
     * The optional phtml file name
     * @var String 
     */
    private $_phtmlScriptFileName;

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
    private $_templateText = '';

    /**
     * The template language as an array of array. Each 0 element beginning
     * with '/' is considered as a regular expression
     * 
     * @var array
     */
    private static $_Token = [
        // escaping {
        ['/%{%(.*)%}%/','{$1}',\TRUE],
        // php tags
        ['{php}', '<?php '],
        ['{/php}', '?>'],
        // foreach with and without key
        ['/{foreach\((\w+),(\w+),(\w+)\)}/i', '<?php foreach($$1 as $$2=>$$3):?>'],
        ['/{foreach\((\w+),(\w+)\)}/i', '<?php foreach($$1 as $$2):?>'],
        ['{endforeach}', '<?php endforeach;?>'],
        // if then else
        ['/{if\((.+?)\)}/', '<?php if($1):?>'],
        ['{else}', '<?php else: ?>'],
        ['{endif}', '<?php endif;?>'],
        // special variables
        //@todo better treat them in View
        ['{ITEM}','<?=$this->ITEM?>'],
        ['{KEY}','<?=$this->KEY?>'],
        ['{LOOPKEY}','<?=$this->LOOPKEY?>'],
        ['{CURRENTLOOPKEY}','<?=$this->CURRENTLOOPKEY?>'],
        ['{ALLDATA}','<?=$this->ALLDATA?>'],
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
    function __construct($iViewScriptName, $view = \NULL) {
        $this->_iViewScriptName = $iViewScriptName;
        $this->_view = $view;
        if (!is_null($view)) {
            $this->_loadTemplate();
        }
    }

    /**
     * An manual accessor to the template text (for quoted view)
     * 
     * @param string $text
     */
    function setText($text) {
        $this->_templateText = $text;
    }

    /**
     * The .iview file name
     * 
     * @return string
     */
    public function getIViewScriptName() {
        return $this->_iViewScriptName;
    }

    /**
     * An interface to the loader to get the content of the template from
     * an .iview file, or if possible, from an existing .phtml translation
     * 
     * @throws \Iris\Exceptions\LoaderException
     */
    private function _loadTemplate() {
        $viewType = $this->_view->getViewType();
        $loader = ie\Loader::GetInstance();
        $iviewScriptName = $this->_iViewScriptName;
        $view = $this->_view;
        try {
            // the case where a scriptName has been explicitely required (renderNow)
            if (!is_null($iviewScriptName)) {
                $viewFile = $loader->loadView($iviewScriptName, "scripts", $view->getResponse());
            }
            else {
                $viewFile = $loader->loadView($view->getViewScriptName(), $view->viewDirectory(), $view->getResponse());
            }
        }
        catch (\Iris\Exceptions\LoaderException $l_ex) {
            throw new \Iris\Exceptions\LoaderException("Problem with $viewType " .
                    $l_ex->getMessage(), NULL, $l_ex);
        }
        //////static::$_LastUsedScript = $viewFile; // for debugging purpose
        $scriptFileName = IRIS_ROOT_PATH . '/' . $viewFile;
        $this->_view->SetLastScriptName($scriptFileName);
        $this->_phtmlScriptFileName = $scriptFileName . '.phtml';
        $read = \FALSE;
        if (self::$_CacheTemplate and file_exists($this->_phtmlScriptFileName)) {
            $file = file($this->_phtmlScriptFileName);
            $this->_toBeParsed = \FALSE;
            $read = \TRUE;
        }
        if (!$read) {
            $file = file($scriptFileName);
            $this->_toBeParsed = \TRUE;
        }
        $this->_templateText = $file;
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
            $phtml = implode("", $this->_templateText);
        }
        else {
            $inStyle = $inScript = FALSE;
            foreach ($this->_templateText as &$line) {
                if (strpos($line, '<style') !== FALSE) {
                    $inStyle = TRUE;
                }if (strpos($line, '<script') !== FALSE) {
                    $inScript = TRUE;
                }
                if (!$inStyle and !$inScript) {
                    foreach (self::$_Token as $tokens) {
                        if ($tokens[0][0] == '/') {
                            $modLine = preg_replace($tokens[0], $tokens[1], $line);
                        }
                        else {
                            $modLine = str_replace($tokens[0], $tokens[1], $line);
                        }
                        if($line!=$modLine){
                            $line = $modLine;
                            if(isset($tokens[2]))
                                break;
                        }
                    }/*
                      $line = str_replace("{php}", '<?php ', $line);
                      $line = str_replace("{/php}", '?>', $line);
                      $line = preg_replace("/({\$)(\w*)(})/i", '<?= $$2?>', $line);
                      $line = preg_replace("/({)(.*?)(})/i", '<?= $this->$2?>', $line);
                     */
                }
                if (strpos($line, '</style>') !== FALSE) {
                    $inStyle = FALSE;
                }
                if (strpos($line, '</script>') !== FALSE) {
                    $inScript = FALSE;
                }
            }
            $phtml = implode("", $this->_templateText);
            if (self::$_CacheTemplate and is_writable(dirname($this->_phtmlScriptFileName))) {
                file_put_contents($this->_phtmlScriptFileName, $phtml);
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
                $value = \Iris\Engine\Program::IsDevelopment();
                break;
            case self::CACHE_PRODUCTION:
                $value = \Iris\Engine\Program::IsProduction();
                break;
        }
        self::$_CacheTemplate = $value;
    }

}

