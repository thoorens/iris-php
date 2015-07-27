<?php

namespace Dojo\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This class loads all Dojo script files necessary to
 * run the active parts of the page.
 * All file names have been collected in Dojo\Manager and Dojo\Bubble
 * by the respective dojo helpers.
 *
 */
class Head {

    use \Iris\views\helpers\tLoaderRegister;

    /**
     * A call to the method register() has been added to the creation
     * of the unique instance. This job is usually done by _subclassInit()
     * in the helpers who  are tLoaderRegister. This class is not a helper
     * since it can be called through {dojo_head()}.
     *
     * @staticvar \Dojo\views\helpers\Head $Instance
     * @return \Dojo\views\helpers\Head
     */
    public static function GetInstance() {
        static $Instance = \NULL;
        if (is_null($Instance)) {
            $Instance = new Head();
            $Instance->register();
        }
        return $Instance;
    }

    /**
     * The render collects all javascript and style defined in Dojo\Mananager,
     * \Dojo\Engine\NameSpaceItem and \Dojo\Engine\Bubble
     *
     * @param type $ajaxMode
     * @return string
     */
    public function render($ajaxMode = \FALSE) {
        if (!\Dojo\Manager::IsActive()) {
            return '';
        }
        $manager = \Dojo\Manager::GetInstance();
        $source = $manager->getURL();
        $script = $manager->getScript();
        $theme = \Dojo\Engine\Settings::$Theme;
        $parseOnLoad = \Dojo\Engine\Settings::$ParseOnLoad;
        $debug = \Dojo\Engine\Settings::$Debug;

        // Loads css and js scripts
        $text = '';
        if (!$ajaxMode) {
            foreach ($manager->getStyleFiles() as $file => $dummy) {
                $text .= sprintf('<link rel="stylesheet" type="text/css" href="%s">' . "\n", $file);
            }
            $text .= <<< BASE
<link rel="stylesheet" type="text/css" href="$source/dijit/themes/$theme/$theme.css">
<script>
    dojoConfig = {parseOnLoad: $parseOnLoad, debug:$debug}
</script>
<script type="text/javascript" src="$script">
</script>
BASE;
        }
        // loads necessary scripts for dojo functions
        $text .= "<script type=\"text/javascript\">\n";
        $text .= \Dojo\Engine\NameSpaceItem::RenderAll();
        $text .= \Dojo\Engine\Bubble::RenderAll();
        $text .= "</script>\n";
        return $text;
    }

}

