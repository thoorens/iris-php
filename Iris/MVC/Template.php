<?php

namespace Iris\MVC;

use Iris\Engine\Loader;

// See real class at bottom of file

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
class Template { //implements \Iris\Translation\iTranslatable {

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
     * @var string 
     */
    private $_readScriptFileName;

    /**
     * The PHTML equivalent file name
     * @var string
     */
    private $_phtmlFilename;

    /**
     * If false, indicates that the _templateText is already translated to
     * phtml 
     * @var boolean
     */
    private $_toBeParsed = \TRUE;

    /**
     * The content of the template, as an array of lines
     * 
     * @var string[]
     */
    private $_templateArray = '';

    /**
     * The template language as an array of array. Each 0 element beginning
     * with '/' is considered as a regular expression
     * 
     * @var string[]
     */
    private static $_Token = [
        // escaping {
        ['/%{%(.*)%}%/', '{$1}', \TRUE],
        // assign a new variable shortcut for {php} $var = 'value' {/php}
        ['/{assign\((\w+), ?(.*)\)}/', '<?php $$1 = $2;?>'],
        // a single print
        ['/{=(.*)}/', '<?= $1?>'],
        // php tags
        ['{php}', '<?php '],
        ['{/php}', '?>'],
        ['/{\?(.*)\?}/', '<?php $1; ?>'],
        // for
        ['/{for\((.+), ?(.+), ?(.+)\)}/i', '<?php for($1; $2; $3):?>'],
        ['{/for}', '<?php endfor;?>'],
        // foreach with key
        ['/{foreach\((.+): ?(\w+), ?(\w+)\)}/i', '<?php foreach($1 as $$2=>$$3):?>'],
        ['/{foreach\((\w+), ?(\w+), ?(\w+)\)}/i', '<?php foreach($$1 as $$2=>$$3):?>'],
        // foreach without key
        ['/{foreach\((.+): ?(\w+)\)}/i', '<?php foreach($1 as $$2):?>'],
        ['/{foreach\((\w+), ?(\w+)\)}/i', '<?php foreach($$1 as $$2):?>'],
        ['{/foreach}', '<?php endforeach;?>'],
        // if then else
        ['/{if\((.+?)\)}/', '<?php if($1):?>'],
        ['{else}', '<?php else: ?>'],
        ['{/if}', '<?php endif;?>'],
        // while
        ['/{while\((.+?)\)}/', '<?php while($1):?>'],
        ['{/while}', '<?php endwhile;?>'],
        // switch equivalence (the dummy "c"ase '':" is required to avoid a PHP error.
        // A PHP closing tag followed by an opening tag is not accepted in this context).
        ['/{section\((.+?)\)}/', "<?php switch($$1): case '':?>"],
        ['{/section}', '<?php endswitch;?>'],
        ['/{block\(\)}/', '<?php default :?>'],
        ['/{block\((.+?)\)}/', '<?php case $1 :?>'],
        ['{/block}', '<?php break;?>'],
        // special variables
        //@todo better treat them in View
        ['{ITEM}', '<?=$this->ITEM?>'],
        ['{KEY}', '<?=$this->KEY?>'],
        ['{D_ITEM}', '<?=$this->D_ITEM?>'],
        ['{D_KEY}', '<?=$this->D_KEY?>'],
        ['{D_ALLDATA}', '<?=$this->D_ALLDATA?>'],
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
        if ($view !== \NULL) {
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
            if ($iviewScriptName !== \NULL) {
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
        if ($this->_getCacheTemplate() and file_exists("$scriptFileName.phtml")) {
            $file = file("$scriptFileName.phtml");
            $this->_toBeParsed = \FALSE;
            $this->_readScriptFileName = "$scriptFileName.phtml";
            $read = \TRUE;
        }
        if (!$read) {
            $extension = (strpos(basename($scriptFileName), '.') === FALSE) ? '.iview' : '';
            $file = file($scriptFileName . $extension);
            $this->_phtmlFilename = "$scriptFileName.phtml";
            $this->_readScriptFileName = $scriptFileName . $extension;
            $this->_toBeParsed = \TRUE;
        }
        $this->_templateArray = $file;
    }

    /**
     * Explores the array template and replace all template tags 
     * by php and or html code. Returns a string.
     *  
     * @param string[] $template
     * @return string 
     */
    public function renderTemplate() {
        if (!$this->_toBeParsed) {
            $phtml = implode("", $this->_templateArray);
        }
        else {
            $literal = FALSE;
            $commentRunning = \FALSE;
            foreach ($this->_templateArray as &$line) {
                // delimiters for comments: all lines where the delimiters appear will be wiped
                if (preg_match('{COMMENT}', $line) or $commentRunning) {
                    if (preg_match('{/COMMENT}', $line)) {
                        $commentRunning = \FALSE;
                    }
                    else {
                        $commentRunning = \TRUE;
                    }
                    $line = '';
                }
                else {
                    // a monoline comment is wiped out 
                    $line = preg_replace('$(.*){REM}.*{/REM}(.*)$i', '$1$2', $line);
                    // in literal context, all braced text are considered as not to be treated
                    if (strpos($line, '{literal}') !== FALSE) {
                        $literal = \TRUE;
                        $line = str_replace('{literal}', '', $line);
                    }
                    elseif (strpos($line, '{/literal}') !== FALSE) {
                        $literal = \FALSE;
                        $line = str_replace('{/literal}', '', $line);
                    }
                    // in non literal context, search for iview tokens
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
            }
            $phtml = implode("", $this->_templateArray);
            if ($this->_getCacheTemplate() and is_writable(dirname($this->_phtmlFilename))) {
                file_put_contents($this->_phtmlFilename, $phtml);
            }
        }
        return $phtml;
    }

    /**
     * Gets the catch template status for the application
     * 
     * @return boolean
     */
    private function _getCacheTemplate() {
        switch (\Iris\SysConfig\Settings::$CacheTemplate) {
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
        return $value;
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
