<?php

namespace Dojo\Engine;

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
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ *
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
        $theme = \Dojo\Engine\Settings::GetTheme();
        $parseOnLoad = \Dojo\Engine\Settings::GetParseOnLoad();
        $debug = \Dojo\Engine\Settings::GetDebug();

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

