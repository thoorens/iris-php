<?php

namespace Iris\Forms;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * A standard form factory can be used to create standard HTML 4 forms
 * and elements.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class StandardFormFactory extends _FormFactory {

    /**
     * The factory type name (here 'html')
     * 
     * @var string
     */
    protected static $_FactoryType = self::HTML;

    /**
     *
     * @param type $name
     * @return Form 
     */
    public function createForm($name) {
        switch (\Iris\SysConfig\Settings::$HTMLType) {
            case \Iris\SysConfig\Settings::HTML4:
                $factory = $this;
                break;
            case \Iris\SysConfig\Settings::HTML5:
                $factory = $this;
                break;
            case \Iris\SysConfig\Settings::HTMLAuto:
                $factory = $this;
                //$factory = $this->_getFactory();
                break;
        }
        $form = new \Iris\Forms\Elements\Form($name);
        $form->setFormFactory($factory);
        return $form;
    }

    protected function _getFactory() {
        $HTTP_USER_AGENT = $_SERVER;
        i_d($HTTP_USER_AGENT['HTTP_USER_AGENT']);
        $browser = get_browser($HTTP_USER_AGENT);
        i_d($browser);
    }

}
