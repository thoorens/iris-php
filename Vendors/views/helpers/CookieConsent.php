<?php
namespace Vendors\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2015 Jacques THOORENS
 */


class CookieConsent extends _VendorsHelper {

    private $_message = 'This site use cookies';
    private $_dismiss = "D'accord";
    private $_learnMore = "(Détails et explications)";
    private $_link = "http://u3a.be/main/cookies/info";
    private $_theme = "dark-bottom";

    /**
     * 
     * @param mixed $param
     * @return \Vendors\views\helpers\CookieConsent
     */
    public function help($param = \NULL){
        if(is_null($param)){
            $value = $this->render();
        }
        elseif($param == 'PARAM'){
            $value = $this;
        }
        return $value;
    }

    private function render() {
        
        $this->HelperCall('JavascriptLoader', ['SilkCC','/!documents/file/javascript/cookie_consent.js']);
        $message = $this->_message;
        $dismiss = $this->_dismiss;
        $learnMore = $this->_learnMore;
        $link = $this->_link;
        $theme = $this->_theme;
        return <<<TEXT
<!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent --> 
<script type="text/javascript">
    window.cookieconsent_options = {
    "message":"Ce site utilise parfois des cookies. Une nouvelle loi européenne nous oblige à vous informer.",
    "dismiss":"D'accord",
    "learnMore":"(Détails et explications)",
    "link":"http://u3a.be/main/cookies/info",
    "theme":"dark-bottom"};alert('Hello');
</script>
        
<!--script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script-->        
<!-- End Cookie Consent plugin -->

TEXT;
    }

//En continuant votre navigation sur ce site, vous acceptez l'utilisation des cookies afin d'assurer le bon déroulement de votre visite et de réaliser des statistiques d'audience.En savoir plus et gérer les cookies
    
    // Version septembre 2016
//   <!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
//{literal}
//<script type="text/javascript">
//    window.cookieconsent_options = {
//    "message":"Ce site utilise parfois des cookies. Une nouvelle loi européenne nous oblige à vous informer.",
//    "dismiss":"D'accord",
//    "learnMore":"(Détails et explications)",
//    "link":"http://u3a.be/main/cookies/info",
//    "theme":"dark-bottom"};
//</script>
//{/literal}

////<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.10/cookieconsent.min.js"></script>
//<!-- End Cookie Consent plugin -->

    
}

