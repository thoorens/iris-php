<?php

namespace modules\acl\controllers;

/**
 * Project : srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of password
 * 
 * @author 
 * @license 
 */
class password extends _acl {

    public function indexAction() {
        // this Title var is required by the default layout defined in _acl
        $this->__Title = $this->callViewHelper('welcome', 1);
        $mode = [\Iris\Users\_Password::MODE_IRIS, \Iris\Users\_Password::MODE_PHP55, \Iris\Users\_Password::MODE_PHP54];
        $basePassword = "motdepasse";
        foreach ($mode as $index => $currentMode) {
            $password = $basePassword . $index;
            $hash = \Iris\Users\_Password::EncodePassword($password, $currentMode);
            $result = \Iris\Users\_Password::VerifyPassword($password, $hash, $currentMode);
            $tab[$index][] = $currentMode;
            $tab[$index][] = $password;
            $tab[$index][] = $hash;
            $tab[$index][] = $result ? 'TRUE' : 'FALSE';
        }
        $this->__tab = $tab;
    }

}
