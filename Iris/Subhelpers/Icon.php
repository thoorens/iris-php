<?php
namespace Iris\Subhelpers;
/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * A return to an old version of Icon (before the integration in _SuperlLink family
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Icon {

    private $_tooltipTemplate;

    /**
     * The icon operation name
     * 
     * @var string 
     */
    private $_operationName;

    /**
     * Determines if the icon/operation is predefined (the icon files are present in ILO)
     * 
     * @var boolean 
     */
    private $_predefined = \FALSE;

    /**
     * Determines if the icon/link must use an id (for editing a line)
     * 
     * @var boolean 
     */
    private $_urlParam;

    /**
     * Some icon/operation have a predefined URL 
     * 
     * @var string
     */
    private $_specialUrl = \NULL;

    function __construct($name, $tooltipTemplate, $urlParam = '') {
        $this->_operationName = $name;
        if (strpos('create_read_delete_update_upload_first_previous_next_last', $name) !== \FALSE) {
            $this->_predefined = \TRUE;
        }
        $this->_tooltipTemplate = $tooltipTemplate;
        $this->_urlParam = $urlParam;
    }

    /**
     * Final render for the icon
     * 
     * @param boolean $active By default use an active icon (may not active)
     * @return string
     */
    function render($active) {
        $ManagerClass = \Iris\SysConfig\Settings::$DefaultModelLibrary.'crud\CrudIconManager';
        $manager = $ManagerClass::GetInstance();
        if ($this->_predefined) {
            $dir = $manager->getSystemIconDir();
        }
        else {
            $dir = $manager->getIconDir();
        }
        $name = $this->_operationName;
        if (!$active) {
            $name = $name . "_des";
            $toolTip = $manager->_('Operation not possible in context');
            $html = \Iris\views\helpers\Link::HelperCall('image', ["$name.png", "Icone $name", $toolTip, $dir]);
        }
        else {
            $help = $manager->makeTooltip($name);
            $ref = $manager->makeReference($this, $this->_urlParam);
            $linkParams = [$name, $ref, $help];
            // we force the string conversion now (if not, problem if we have various icons made in sequence)
            $html = \Iris\views\helpers\Link::HelperCall('link', $linkParams)->setInternalImageFolder($dir)->image("$name.png")->__toString();
        }
        return $html;
    }

    /**
     * Returns a template for the icon tooltip
     * 
     * @return string
     */
    public function getTooltipTemplate() {
        return $this->_tooltipTemplate;
    }

    /**
     * Accessor get for the icon operation name 
     * @return string
     */
    public function getName() {
        return $this->_operationName;
    }

    /**
     * Sets a special URL for the operation/icon
     * @param string $url
     */
    public function setSpecialUrl($url) {
        $this->_specialUrl = $url;
    }

    public function getSpecialUrl() {
        return $this->_specialUrl;
    }

    public function getUrlParam() {
        return $this->_urlParam;
    }

}
