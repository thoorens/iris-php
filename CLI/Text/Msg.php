<?php

Messages::$Error['Fr']['ERR_INTERACT'] = "--interactive doit venir après une option  --createproject";
Messages::$Error['En']['ERR_INTERACT'] = "--interactive must come after  --createproject";

Messages::$Error['Fr']['ERR_BADINI'] = "Votre fichier de paramètres semble incorrect. Veuillez vérifier votre configuration en fonction des instructions du manuel. 
";
Messages::$Error['En']['ERR_BADINI'] = "Your param file does not seem to be a valid one. Please check your configuration according to the manual instructions.
";

Messages::$Error['Fr']['ERR_NOPERSONNALDIR'] = "iris.php n'est pas en mesure de lire ou écrire vos fichiers de paramètres. IRIS PHP CLI ne sera pas en mesure de fonctionner sur votre système. Désolé.";
Messages::$Error['En']['ERR_NOPERSONNALDIR'] = "iris.php is not able to find where to read or write your parameter files.
IRIS PHP CLI will not be functional on your system, sorry.";

Messages::$Error['Fr']['MSG_NEWPARAMETERFILE'] = "Votre fichier de paramètres %s a été créé. 
Vous pouvez maintenant utiliser ce programme normalement (iris.php --help pour de l'aide)";
Messages::$Error['En']['MSG_NEWPARAMETERFILE'] = "Your parameter file %s has been created.
Now you can use this program (\"iris.php --help\"; for help].
";

Messages::$Error['Fr']['ERR_BADIRISFILE'] = "Le chemin que vous avez indiqué ne semble pas être un répertoire d'une installation valide d'IRIS-PHP";
Messages::$Error ['En']['ERR_BADIRISFILE'] = ";The suggested path does not seem to be a valid Iris-PHP installation directory.";

//Messages::$Error['Fr']['ERR_SUPPLYIRISDIR'] = "Vous devez fournir le chemin de votre installation d'IRIS-PHP pour intitaliser votre fichier de paramètres";
//Messages::$Error['En']['ERR_SUPPLYIRISDIR'] = "You must supply the path to your Iris-PHP installation to init your parameter file
//before beeing able to use this program. See documentation if necessary.";

Messages::$Error['Fr']['ERR_NOPROJECTFILE'] = "Il ne semble pas exister de fichier projects.ini. Créez au préalable un projet: 
iris.php --createproject /path/to/the/new/project";
Messages::$Error['En']['ERR_NOPROJECTFILE'] = "There seems to exist no projects.ini file. First create a project
iris.php --createproject /path/to/the/new/project";

Messages::$Error['Fr']['ERR_NOBDFILE'] = "Aucune base de données n'a été définie par l'utilisateur courant (pas de /home/user/.iris/db.ini).";
Messages::$Error['En']['ERR_NOBDFILE'] = "No database has been defined by the current user (no /home/user/.iris/db.ini).";

Messages::$Error['Fr']['ERR_NODEFPROJECT'] = "Il semble que vous n'ayez plus de projet par défaut dans votre environnement. 
Choisissez-en un avec  'iris.php --setdefaultproject' ou créez-en un avec'iris.php --createproject'.";
Messages::$Error['En']['ERR_NODEFPROJECT'] = "You seem to have no more default project in your environment.
Select one with 'iris.php --setdefaultproject' or create one with 'iris.php --createproject'.
";

Messages::$Error['Fr']['ERR_BADFILENAME'] = "Le programme a tenté la lecture d'un fichier avec un nom incorrect";
Messages::$Error['En']['ERR_BADFILENAME'] = "The program tried to read a file with an incorrect name.";

Messages::$Error['Fr']['ERR_ABSOLUTEPATH'] = "Le chemin vers le projet doit être absolu; ex. /srv/projectdir";
Messages::$Error['En']['ERR_ABSOLUTEPATH'] = "The path to the project must be absolue, e. g.  /srv/projectdir";

Messages::$Error['Fr']['ERR_BADMODULE'] = "--module or -M  n'est pas autorisé dans ce contexte";
Messages::$Error['En']['ERR_BADMODULE'] = " --module or -M is not allowed in this context";

Messages::$Error['Fr']['ERR_BADCOONTROLLER'] = "--controller or -C  n'st pas autorisé dans ce contexte";
Messages::$Error['En']['ERR_BADCOONTROLLER'] = "--controller or -C is not allowed in this context";

Messages::$Error['Fr']['ERR_BADACTION'] = "--action or -A is n'est pas autorisé dans ce contexte";
Messages::$Error['En']['ERR_BADACTION'] = " --action or -A is not allowed in this context";

Messages::$Error['Fr']['ERR_BADENTITY'] = "--entitygenerate or -e n'est pas autorisé dans ce contexte";
Messages::$Error['En']['ERR_BADENTITY'] = "--entitygenerate or -e is not allowed in this context";


Messages::$Error['Fr']['ERR_PASSWORD'] = "--password doit être une option unique suivie par la suite de caractères du mot de passe";
Messages::$Error['En']['ERR_PASSWORD'] = " --password must be a unique option followed by the password characters";

Messages::$Error['Fr']['ERR_SHOW'] = "--show est une option unique suivie d'une commande (list; status; ini)";
Messages::$Error['En']['ERR_SHOW'] = "--show must be a unique option followed by its command value (list, status, ini)";

Messages::$Error['Fr']['ERR_APPLICATION'] = "--applicationdir peut seulement venir  après une option --createproject";
Messages::$Error['En']['ERR_APPLICATION'] = " --applicationdir may only come after the --createproject option";

Messages::$Error['Fr']['ERR_LIBRARY'] = "--librarydir peut seulement venir après l'option --createproject";
Messages::$Error['En']['ERR_LIBRARY'] = "--librarydir may only come after the --createproject option";

Messages::$Error['Fr']['ERR_PUBLIC'] = "--publicdir doit venir après une option  --createproject";
Messages::$Error['En']['ERR_PUBLIC'] = "--publicdir may only come after the --createproject option";

Messages::$Error['Fr']['ERR_URL'] = "--url peut seulement venir après l'option --createproject";
Messages::$Error['En']['ERR_URL'] = "--url may only come after the --createproject option";

Messages::$Error['Fr']['ERR_PROJECT'] = "--%s ne peut venir après une autre option (à l'exception de --verbose)";
Messages::$Error['En']['ERR_PROJECT'] = " --%s cannot be used after another option (except --verbose)";

Messages::$Error['Fr']['ERR_GEN'] = "--%s ne peut venir après une autre option (except --module; --controller ou --action)";
Messages::$Error['En']['ERR_GEN'] = "--%s cannot be used after another option (except --module, --controller or --action)";

Messages::$Error['Fr']['ERR_UNKNOWNPROJECT'] = "Le project nommé %s n'existe pas";
Messages::$Error['En']['ERR_UNKNOWNPROJECT'] = "The project named %s does not exist";

Messages::$Error['Fr']['ERR_CANNOTDELETE'] = "Le projet %s n'existe pas et ne peut donc être effacé.";
Messages::$Error['En']['ERR_CANNOTDELETE'] = "The project %s does not exist and cannot be deleted.";

Messages::$Error['Fr']['ERR_LOCKED'] = "Attention : le projet %s est protégé. Vous devriez utiliser d'abord  --unlockproject.";
Messages::$Error['En']['ERR_LOCKED'] = "Caution : the project %s is locked. You may wish to use --unlockproject.";

Messages::$Error['Fr']['ERR_CONFIRM'] = "Vous devez terminer la commande --removeproject par le mot \"confirm\"; pour procéder à l'effacement réel du projet.";
Messages::$Error['En']['ERR_CONFIRM'] = "You must terminate the command removeproject by the word 
\"confirm\"; to actually delete the project.";

Messages::$Error['Fr']['MSG_DELETED'] = "Le projet %s a été complètement effacé.";
Messages::$Error['En']['MSG_DELETED'] = "The project %s has been completely removed.";

Messages::$Error['Fr']['MSG_NOMOREDEF'] = "Il n'y a plus de projet par défaut.
Employez 'iris.php --selectdefaultproject' pour en définir un autre.";
Messages::$Error['En']['MSG_NOMOREDEF'] = "There is no more default project.
Use 'iris.php --selectdefaultproject' to define a new one.";



Messages::$Error['Fr']['MSG_LANGUAGE'] = "Change le langage de l'interface en %s. ";
Messages::$Error['En']['MSG_LANGUAGE'] = "Change the interface language to %s.";

Messages::$Error['Fr']['MSG_LOCK'] = "Le projet %s est maintenant protégé contre l'effacement.";
Messages::$Error['En']['MSG_LOCK'] = "The project %s is now protected against deletion.";

Messages::$Error['Fr']['MSG_UNLOCK'] = "Le projet %s n'est plus protégé contre l'effacement.";
Messages::$Error['En']['MSG_UNLOCK'] = "The project %s is no longer protected against deletion .";

Messages::$Error['Fr']['ERR_LANG'] = "--language s'emploie comme option unique et doit être suivie du symbole de langue (%s)";
Messages::$Error['En']['ERR_LANG'] = " --language must be a unique option followed by its name value (%s)";

Messages::$Error['Fr']['ERR_LISTLANG'] = "Le langage de l'interface doit être choisi parmi %s";
Messages::$Error['En']['ERR_LISTLANG'] = "Interface language must be choosed among %s";

Messages::$Error['Fr']['MSG_NEWDEF'] = "%s est maintenant votre project par défaut.";
Messages::$Error['En']['MSG_NEWDEF'] = "%s is now your default project.";

Messages::$Error['Fr']['ERR_INCOMPLETE'] = "La ligne de commande est incomplète ou incohérente.
               Voir iris.php -- help";
Messages::$Error['En']['ERR_INCOMPLETE'] = "The command line is incomplete or incoherent.
                    See iris.php --help
";

Messages::$Error['Fr']['MSG_NODEF'] = "Il n'y a plus de projet par défaut.
Vous pouvez en rétablir un  
      'iris.php --selectdefaultproject'    // en sélectionnant un projet existant
      'iris.php --createproject'                // créant un nouveau projet
";
Messages::$Error['En']['MSG_NODEF'] = "There is no more default project.
Use
      'iris.php --selectdefaultproject'    to select another one or
      'iris.php --createproject' to create a new project";


Messages::$Error['Fr']['ERR_CANNOTFIND'] = "Le dossier %s n'existe pas sur le disque dur. Le project n'existe pas ou n'existe plus. 
La meilleure solution est d'effacer la section [%s] dans /home/user/.iris/projects.ini";
Messages::$Error['En']['ERR_CANNOTFIND'] = "No %s directory exists on the hard disk. The project does not exist or does exist anymore.
The best solution is to manually delete the section [%s] in /home/user/.iris/projects.ini.";

Messages::$Error['Fr']['ERR_NAMEEXISTS'] = "Un projet nommé %s semble déjà exister. Proposez un autre nom.";
Messages::$Error['En']['ERR_NAMEEXISTS'] = "A project named %s seems to existe. Choose another name.";

Messages::$Error['Fr']['ERR_NOTDEV'] = "Cette fonction est en cours de développement";
Messages::$Error['En']['ERR_NOTDEV'] = "Still not developped";


Messages::$Error['Fr']['ERR_INTERACT'] = "--interactive doit venir après une option  --createproject";
Messages::$Error['En']['ERR_INTERACT'] = " --interactive may only come after the --createproject option";

Messages::$Error['Fr']['ERR_BADDBFUNC'] = "La fonction --database %s n'est pas implémentée";
Messages::$Error['En']['ERR_BADDBFUNC'] = "Function --database %s not implemented.";

Messages::$Error['Fr']['MSG_NEWFILEBU'] = "Le fichier %s existait déjà. Une copie de sécurité a été faite.";
Messages::$Error['En']['MSG_NEWFILEBU'] = "The file %s already exists. A backup has been made.";

Messages::$Error['Fr']['MSG_PASSWORDIRIS'] = "Empreinte du mot de passe (algorithme interne) :";
Messages::$Error['En']['MSG_PASSWORDIRIS'] = "Hashed password (internal algorithm) :";

Messages::$Error['Fr']['MSG_PASSWORDPHP'] = "Empreinte du mot de passe (fonction PHP 5.5 ou émulation) :";
Messages::$Error['En']['MSG_PASSWORDPHP'] = "Hashed password (PHP 5.5 algorithm or emulation) :";

Messages::$Error['Fr']['ERR_PWDINCLI'] = "Votre système n'est pas capable de générer des mots de passe à l'aide de l'algorithme PHP 5 en ligne de commandes.
Utilisez la commande interne /!admin/password pour générer ce type d'empreintes.";
Messages::$Error['En']['ERR_PWDINCLI'] = "Your system is unable to generate PHP 5.5 password hash in CLI.
Use the internal /!admin/password URL to generate this type of hashes.";

Messages::$Error['Fr']['ERR_INHELP'] = "Le message d'aide %s n'est pas disponible.";
Messages::$Error['En']['ERR_INHELP'] = "No help message is available for %s";

Messages::$Error['Fr']['ERR_EXISTINGFOLDER'] = "Un dossier %s existe déjà. Choisissez un autre nom.";
Messages::$Error['En']['ERR_EXISTINGFOLDER'] = "A folder %s already exists.Choose another name.";

Messages::$Error['Fr']['MSG_PUBLIC'] = "Création du dossier public et de ses fichiers (%s/...).";
Messages::$Error['En']['MSG_PUBLIC'] = "Making public directories and files (%s/...).";

Messages::$Error['Fr']['MSG_HTACCESS'] = "Vous devez modifier %s/.htaccess pour l'adapter aux exigences de votre gestionnaire de site.";
Messages::$Error['En']['MSG_HTACCESS'] = "You may have to edit %s/.htaccess to suit your provider requirements.";

Messages::$Error['Fr']['MSG_APPLICATION'] = "Création du dossier application et des ses fichiers (%s/...).";
Messages::$Error['En']['MSG_APPLICATION'] = "Making application directories and files (%s/...).";

Messages::$Error['Fr']['ERR_EXISTING_MCA'] = "La page %s existe déjà.";
Messages::$Error['En']['ERR_EXISTING_MCA'] = "Existing page %s.";

Messages::$Error['Fr']['ERR_EXISTING_C'] = "Le contrôleur %s existe déjà";
Messages::$Error['En']['ERR_EXISTING_C'] = "Controller %s already exists.";

Messages::$Error['Fr']['ERR_EXISTING_A'] = "L'action %s existe déjà";
Messages::$Error['En']['ERR_EXISTING_A'] = "Action %s already exists.";

Messages::$Error['Fr']['ERR_AUTOCODE'] = "Le code ne peut se modifier lui-même";
Messages::$Error['En']['ERR_AUTOCODE'] = "The code cannot modify itself!";

Messages::$Error['Fr']['ERR_NOCLASS'] = "La classe %s n'existe pas. N'oubliez pas le double \\ en ligne de commande";
Messages::$Error['En']['ERR_NOCLASS'] = "Class %s does not exist. Don't forget to use double \\ in CLI.";

Messages::$Error['Fr']['ERR_FILE_EXISTS'] = "Le fichier %s existe. Il semble prudent de vérifier son contenu";
Messages::$Error['En']['ERR_FILE_EXISTS'] = "The file %s exists. It may be convenient to check its content.";

Messages::$Error['Fr']['ERR_BAD_OS'] = "Actuellement, IRIS CLI ne supporte pas %s";
Messages::$Error['En']['ERR_BAD_OS'] = "For now, Iris CLI does not support %s";

Messages::$Error['Fr']['ERR_NOPROJECT'] = "Le projet '%s' n'existe pas. Choisissez-en un autre.\n";
Messages::$Error['En']['ERR_NOPROJECT'] = "The project '%s' doesn't exist. Choose another one.\n";

Messages::$Error['Fr']['ERR_OVER'] = "La classe '%s' ne peut être surchargée à l'aide de CLI
Surchargez '__construct' in 'public/bootstrap.php et charger votre propre classe à la main";
Messages::$Error['En']['ERR_OVER'] = "Class '%s' can't be overridden through CLI. 
Overwrite __construct in public/Bootstrap.php instead and load your own class manually.";

Messages::$Error['Fr']['MSG_CREATE_M'] = "Le module %s a été créé.";
Messages::$Error['En']['MSG_CREATE_M'] = "Module %s has been created";
Messages::$Error['Fr']['MSG_CREATE_C'] = "Le contrôleur %s a été créé.";
Messages::$Error['En']['MSG_CREATE_C'] = "Controller %s has been created.";
Messages::$Error['Fr']['MSG_CREATE_A'] = "L'action %s a été créée.";
Messages::$Error['En']['MSG_CREATE_A'] = "Action %s has been created.";

Messages::$Error['Fr']['MSG_VIEWTEXT'] = "Vous pouvez maintenannt commencer à modifier cette page.";
Messages::$Error['En']['MSG_VIEWTEXT'] = "Now you can begin to modify this page...";


# Related to databases errors
Messages::$Error['Fr']['ERR_DB_NOTINI'] = "La base de données %s n'est pas gérée à l'aide d'un fichier INI";
Messages::$Error['En']['ERR_DB_NOTINI'] = "The database %s not managed by an INI file.";

Messages::$Error['Fr']['ERR_NODBINI'] = "Aucune base de données n'a été définie par l'utilisateur courant";
Messages::$Error['En']['ERR_NODBINI'] = "No database has been defined by the current user.";

Messages::$Error['Fr']['ERR_DBID'] = "Aucune base de données avec l'identifiant %s n'a été trouvée dans le système.
Choisissez un autre nom ('iris.php -B list' pour voir les identifiants connus)
ou créez-en un à l'aide de 'iris.php -B create %s'.";

Messages::$Error['En']['ERR_DBID'] = "No database with id %s has been found in the system.
Choose another name ('iris.php -B list' to see the existing names)
or create it before whith 'iris.php -B create %s'.";

Messages::$Error['Fr']['MSG_SWITCHDB'] = "Changer la base de données de %s à %s\n";
Messages::$Error['En']['MSG_SWITCHDB'] = "Switching database from %s to %s\n";

Messages::$Error['Fr']['ERR_UNDEFDB'] = "La base de données '%s' n'est pas définie";
Messages::$Error['En']['ERR_UNDEFDB'] = "The database '%s' is not defined";

Messages::$Error['Fr']['ERR_DBNONE'] = "Pas de base de données associées au projet";
Messages::$Error['En']['ERR_DBNONE'] = "No database associated to the project";

Messages::$Error['Fr']['ERR_DBINI'] = "Un fichier '%s' existe déjà.
Voulez-vous le modifier pour concorder avec les praramètres de votres base de données?
Vous pouvez aussi le supprimer et relancer 'iris.php --makedbini'.\n";
Messages::$Error['En']['ERR_DBINI'] = "A file '%s' already exists.
Would you please edit it by hand according to your database settings?
You can also delete it and rerun 'iris.php --makedbini'.\n";

Messages::$Error['Fr']['ERR_DBSHORT'] = "L'identifiant de base de données doit comporter au moins une lettre";
Messages::$Error['En']['ERR_DBSHORT'] = "The database id must be at least one letter long.";

Messages::$Error['Fr']['ERR_EXISTID'] = "Une base de données d'identifiant '%s' existe déjà. Vous pouvez l'utiliser";
Messages::$Error['En']['ERR_EXISTID'] = "A database with the id '%s' is already referenced. You can use it.";

Messages::$Error['Fr']['ERR_BADDBINI'] = "--makedbini or -I n'est pas autorisé dans ce contexte";
Messages::$Error['En']['ERR_BADDBINI'] = " --makedbini or -I is not allowed in this context";

Messages::$Error['Fr']['ERR_BADSELECTDB'] = "--selectbase or -b is n'est pas autorisé dans ce contexte";
Messages::$Error['En']['ERR_BADSELECTDB'] = "--selectbase or -b is not allowed in this context";

Messages::$Error['Fr']['ERR_DBPARAM'] = "La valeur %s n'est pas une sous-commande attendue dans ce contexte";
Messages::$Error['En']['ERR_DBPARAM'] = "Value %s is not an expected subcommand in this context";

Messages::$Error['Fr']['MSG_PW'] = "Mot de passe       : == défini dans le fichier (caché pour raison de sécurité) ==";
Messages::$Error['En']['MSG_PW'] = "Password : == defined in file (not listed for security reasion) ==";

Messages::$Error['Fr']['MSG_DBINI'] = "Géré par un fichier INI : %s\n";
Messages::$Error['En']['MSG_DBINI'] = "Managed by INI file : %s\n";

Messages::$Error['Fr']['MSG_DBTIT'] = "Définition de la base de données par défaut";
Messages::$Error['En']['MSG_DBTIT'] = "Default database definition:";

Messages::$Error['Fr']['MSG_DBPRJNM'] = "Nom du projet : %s";
Messages::$Error['En']['MSG_DBPRJNM'] = "Project name  : %s";

Messages::$Error['Fr']['MSG_DBAD'] = "Type de base de données : %s";
Messages::$Error['En']['MSG_DBAD'] = "Database adapter : %s";

Messages::$Error['Fr']['MSG_DBFN'] = "Nom du fichier : %s";
Messages::$Error['En']['MSG_DBFN'] = "File name : %s";

Messages::$Error['Fr']['MSG_DBHST'] = "Nom d'hôte : %s";
Messages::$Error['En']['MSG_DBHST'] = "Host name : %s";

Messages::$Error['Fr']['MSG_DBNAM'] = "Base de données : %s";
Messages::$Error['En']['MSG_DBNAM'] = "Database : %s";

Messages::$Error['Fr']['MSG_DBUSRNAM'] = "Nom d'utilisateur : %s";
Messages::$Error['En']['MSG_DBUSRNAM'] = "User name : %s";

Messages::$Error['Fr']['MSG_BOOL0'] = "NON";
Messages::$Error['En']['MSG_BOOL0'] = "NO";

Messages::$Error['Fr']['MSG_BOOL1'] = "OUI";
Messages::$Error['En']['MSG_BOOL1'] = "YES";
