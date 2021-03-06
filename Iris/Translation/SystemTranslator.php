<?php
namespace Iris\Translation;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Provides a way to translate system messages and text.
 * In beta, it translate only from english to french
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * @todo Implement language detection
 * 
 */
class SystemTranslator extends \Iris\Translation\_Translator implements \Iris\Design\iSingleton {

    private static $_Instance = NULL;
    private $_french = [
        //        
        // Helpers
        //        
        '(Details)' => '(Détails)',
        // {Head()}
        'Site created with Iris-PHP' => 'Site réalisé avec Iris-PHP',
        // {Boolean()}
        'FALSE' => 'FAUX',
        'TRUE' => 'VRAI',
        'Not a logical value' => 'Valeur non logique',
        //        
        // Errors
        //
        'Error' => 'Erreur',
        'An error has occured. We apologize for the inconvenience.' => "Une erreur s'est produite, veuillez nous en excuser.",
        'A fatal error has occured. We apologize for the inconvenience.' => "Une erreur fatale s'est produite. Veuillez nous en excuser.",
        'Forbidden page' => 'Page inaccessible',
        'Impossible access' => 'Accès impossible',
        "Unkwown fatal error" => 'Erreur fatale indéterminée',
        "Sorry! An error occured in error screen. Back to main page..." => "D&eacute;sol&eacute;! Une erreur s'est produite dans une page d'erreur. Retour &agrave; la page d'accueil",
        "Fatal error" => "Erreur fatale",
        //        
        // Date
        //        
        'Mon' => 'lun',
        'Tue' => 'mar',
        'Wed' => 'mer',
        'Thu' => 'jeu',
        'Fri' => 'ven',
        'Sat' => 'sam',
        'Sun' => 'dim',
        'Monday' => 'lundi',
        'Tuesday' => 'mardi',
        'Wednesday' => 'mercredi',
        'Thursday' => 'jeudi',
        'Friday' => 'vendredi',
        'Saturday' => 'samedi',
        'Sunday' => 'dimanche',
        'Jan' => 'jan',
        'Feb' => 'fev',
        'Mar' => 'mar',
        'Apr' => 'avr',
        'May' => 'mai',
        'Jun' => 'jun',
        'Jul' => 'jul',
        'Aug' => 'aou',
        'Sep' => 'sep',
        'Oct' => 'oct',
        'Nov' => 'nov',
        'Dec' => 'déc',
        'January' => 'janvier',
        'February' => 'février',
        'March' => 'mars',
        'April' => 'avril',
        'May' => 'mai',
        'June' => 'juin',
        'July' => 'juillet',
        'August' => 'août',
        'September' => 'septembre',
        'October' => 'octobre',
        'November' => 'novembre',
        'December' => 'décembre',
// Validators
        'The file corresponds to none of the expected MIME types' =>
        'Le fichier fourni ne correspond à aucun des types MIME attendus',
        'This data is required' => 'Ce champ est requis',
        'Refused character : ' => 'Caractère non autorisé: ',
        'Invalid email adresse' => 'Adresse de courriel invalide',
        // Système
        'Page under construction' => 'Page en travaux',
        'Return to main page' => 'Retour à la page d\'accueil',
        'Role tester' => 'Testeur de rôles',
        'The two passwords do not match' => 'Les deux mots de passe ne sont pas identiques',
        'Return to main site' => "Retour à la page d'accueil",
        'Iris PHP official web site' => 'Site officiel du framework Iris PHP',
        // Admin stuff
        // 
        'Go to administration tools...' => 'Accéder aux outils d\'administration',
        'Quit admin module and return to the site welcome page' => "Quitter le module d'adminstraction et retourner à la page d'accueil",
        'Role tester|/!admin/roles/switch|Switch to a dummy user having a specific role' =>
        'Test des rôles|/!admin/roles/switch|Utiliser un autre utilisateur',
        'ACL management|!admin/roles/acl|Display and edit all Access Control Lists' =>
        "Gestion des droits|!admin/roles/acl|Afficher ou modifier les droits d'accès",
        'Structure management|/!admin/structure/index|Manage modules, controllers and action' =>
        "Structure de l'application|/!admin/structure|Gérer modules, contrôleurs et actionManage modules, controllers and action",
        'Function 4||Future enhancement' =>
        'Fonction 4||A développer',
        'Function 5||Future enhancement' =>
        'Fonction 5||A développer',
// Forms
        'Upload' => 'Téléverser',
        'File to upload:' => 'Fichier à téléverser:',
        'Retype password:' => 'Retapez le mot de passe:',
// CRUD
        "Add %U %E| (type %P)" => "Ajouter %U %E| de type %P",
        'Display %D %E %O' => "Afficher %D %E %O",
        'Modify %D %E %O' => 'Modifier %D %E %O',
        'Delete %D %E %O' => 'Supprimer %D %E %O',
        'Upload %D %E %O' => 'Téléverser %D %E %O',
        'Go to the first «%E»' => 'Aller sur la première fiche «%E»',
        'Go to the previous «%E»' => 'Aller sur la précédente fiche «%E»',
        'Go to the next «%E»' => 'Aller sur la fiche «%E» suivante',
        'Go to the last «%E»' => 'Aller sur la dernière fiche «%E»',
        'Operation not possible in context' => "Fonction indisponible dans ce contexte",
        // Articles
        'the_a' => 'le_un',
        'the_an' => "l'_un",
        'THE_A' => 'la_une',
        'THE_AN' => "l'_une",
        // Forms and elements
        'addOption need the object to be registred (with addTo()).'
        => "addOption s'applique à un objet enregistré (au moyen de addTo()).",
        'Insert' => 'Ajouter',
        'Update' => 'Modifier',
        'Read' => 'Lire',
        'Delete' => 'Supprimer',
        // Login
        'User name:' => "Nom d'utilisateur:",
        'Password:' => 'Mot de passe:',
        'Log In' => 'Connexion',
// DEMO
        'Now you can begin to modify this page...' =>
        'Vous pouvez à présent modifier cette page...',
        'Welcome to IRIS-PHP framework' =>
        'Bienvenue dans le framework IRIS-PHP',
// WorkBench
        'This error screen is intentional and expected' =>
        "Cet écran d'erreur est volontaire et attendu",
        // Internal
        'Site powered by Iris-PHP' => 'Site motorisé par Iris-PHP',
        // Admin Toolbar
        'Available actions' => 'Actions disponibles',
        'Links to all action in the application' => 'Liens vers les actions de toute l\'application',
        'User' => 'Utilisateur',
        'Group' => 'Groupe',
        'Execution time' => 'Temps d\'exécution',
        'Reset the session' => 'Réinitialiser la session',
        'Toolbar managed by Ajax' => "Barre d'administration en mode Ajax",
        // Language management
        'french' => 'français',
        'english' => 'anglais',
        'italian' => 'italien',
        'Choose language ' => 'Le site en ',
        'Current language : '=> 'Langue en cours : ',
// JQuery
        "See more details" => 'Voir plus de détails',
        'Close window' => 'Fermer la fenêtre',
    ];

    public static function GetInstance() {
        if (static::$_Instance == NULL) {
            static::$_Instance = new static();
            self::SetLanguage();
        }
        return static::$_Instance;
    }

    private function __construct() {
        
    }

    public function translate($message, $language = NULL) {
        $language = is_null($language) ? self::$CurrentLanguage : $language;
        if ($language == 'fr' and isset($this->_french[$message])) {
            return $this->_french[$message];
        }
        return $message;
    }

}
