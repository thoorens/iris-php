<?php
// ==========================================================================================================================================
// --help
// ==========================================================================================================================================

Messages::$Help['En']['h'] =
"iris.php
========
This program offers an interface for various commands to create and manage
a site project realized with Iris-PHP. Different parameters are written
in distinct files in /home/username/.iris (under Linux)

To obtain a complete list of the available help screeens, type :

    iris.php -H
";

Messages::$Help['Fr']['h'] =        
"iris.php
========
Ce programme offre une interface pour une série de commandes permettant 
la création et la gestion d''un projet de site web à l'aide dIris-PHP. 
Différents paramètres sont enregistrés dans plusieurs fichiers du dossier 
/home/username/.iris (sous Linux).

Pour obtenir la liste des écrans d'aide disponibles, tapez :

    iris.php -H

"
;
Messages::$Help['Ext']['h'] = 'FALSE';
Messages::$Help['Ext']['h'] ='more';


// ==========================================================================================================================================
// createproject
// ==========================================================================================================================================
Messages::$Help['Fr']['c'] =
"Fonction :
    iris.php --createproject PathToBaseDir  [--publicdir PublicName] [--applicationdir ApplicationName] [--url localURL]
    iris.php -c PathToBaseDir  [-p PublicName] [-a ApplicationName] [-u localURL] 
 
Création d''un projet dans le répertoire PathToBaseDir. Le chemin doit être 
absolu (/path/to/base/dir).
 
La partie visible sera dans PublicName (par défaut ''public''),    
la partie applicative dans ApplicationName (par défaut ''application'')                         
et le site en developpement sera accessible par l''URL spécifiée 
(par défaut mysite.local).
";
Messages::$Help['En']['c'] = 
"Function :
    iris.php --createproject PathToBaseDir  [--publicdir PublicName] [--applicationdir ApplicationName] [--url localURL]
    iris.php -c PathToBaseDir  [-p PublicName] [-a ApplicationName] [-u localURL] 
 
Creates a new project in the folder PathToBaseDir. The path has to be absolute 
(/path/to/base/dir).
 
The visible part will be in PublicName (by default ''public''),    
The application part in ApplicationName (by default ''application'')                         
and the development site will have a specific URL (by default mysite.local).
";
Messages::$Help['Ext']['c'] ='FALSE';

// ==========================================================================================================================================
// removeproject
// ==========================================================================================================================================
Messages::$Help['Fr']['r'] =
"Function :                                                                                                                                           
    iris.php --removeproject PathToBaseDir confirm                                                                                                                                        
    iris.php -r PathToBaseDir confirm                                                                                                                                                     
     
Supprime un projet en effaçant complètement le contenu du répertoire qui le 
contient. Attention cette opération est destructrice et IRREVERSIBLE. 
C'est pour cette raison que le mot 'confirm' doit être ajouté à la commande, 
afin d'éviter les erreurs de manipulation. Sans ce paramètre, la commande 
se contente d'afficher les commandes qui devraient s'exécuter (suppressions de 
fichiers, de liens et de dossiers) . 
";
Messages::$Help['En']['r'] =
"Function :                                                                                                                                           
    iris.php --removeproject PathToBaseDir confirm                                                                                                                                        
    iris.php -r PathToBaseDir confirm                                                                                                                                                     
     
Deletes  a project completely by erasing the content of the directory containing
it. Please note this operation is very destructive. It is for this reason that 
the word 'confirm' should be added at then end of the line, in order to
confirm your intention. Without this parameter, the command only displays 
the commands that should run (deletion of files, links and folders).
";
Messages::$Help['Ext']['r'] ='FALSE';
// ==========================================================================================================================================
// show
// ==========================================================================================================================================
Messages::$Help['En']['s'] =
"Fonction :                                                                                                                                           
    iris.php --show list   
    iris.php -s list
       
Displays the projects of the current user.  

Fonction :
    iris.php --show status
    iris.php -s status

Display the recorded parameters of the current default project (a good way 
to see which project is seen as the default project).

Function :
    iris.php --show ini
    iris.php - s ini

Display the file containing all the project parameters  (/home/usrname/.iris/projects.ini)

Function :
    iris.php --show virtual
    iris.php -s  virtual

Displays what Apache should receive as parameters to recognize the project as 
a virtual server.','Fonction :                                                                                                                                           
    iris.php --show list   
    iris.php -s list
";
Messages::$Help['Fr']['s'] =
"Affiche les projets de l''utilisateur courant.     
                                                                                                                                                                           
Fonction :
    iris.php --show status
    iris.php -s status

Affiche à l''écran les parramètres actuels du projet par défaut
(c''est un bon moyen de vérifier lequel c''est).

Fonction :
    iris.php --show ini
    iris.php - s ini

Affiche à l''écran le contenu entier du fichier de paramètres des projets 
 (/home/usrname/.iris/projects.ini)

Fonction :
    iris.php --show virtual
    iris.php -s  virtual

Reproduit le contenu du fichier destiné à paraméterer le
serveur Apache (utile en cas d''effacement du fichier xxx.virtual).
";
Messages::$Help['Ext']['s'] ='FALSE';
// ==========================================================================================================================================
// verbose
// ==========================================================================================================================================
Messages::$Help['Fr']['v'] =
"Lorsque le paramètre -v ou --verbose intervient, certains messages explicites sont 
ajoutés à l''exécution du programme

      iris.php -v -c
ou
     iris.php --verbose --createproject

";
Messages::$Help['En']['v'] = "";
Messages::$Help['Ext']['v'] ='FALSE';
// ==========================================================================================================================================
// help
// ==========================================================================================================================================
Messages::$Help['Fr']['H'] = "
        iris.php -h=help (ou -h=h)
        iris.php -h=show (ou -h=s)
        iris.php -h=language (ou -h=1)
        iris.php -h=test (ou -h=t)
        iris.php -h=createproject (ou -h=c)
        iris.php -h=removeproject (ou -h=r)
        iris.php -h=interactive (ou -h=i)
        iris.php -h=docproject (ou -h=D)
        iris.php -h=lockproject (ou -h=L)
        iris.php -h=unlockproject (ou -h=U)
        iris.php -h=setdefaultproject (ou -h=d)
        iris.php -h=projectmetadata (ou -h=m)
        iris.php -h=applicationdir (ou -h=a)
        iris.php -h=publicdir (ou -h=p)
        iris.php -h=libraryname (ou -h=l)
        iris.php -h=url (ou -h=u)
        iris.php -h=generate (ou -h=g)
        iris.php -h=controller (ou -h=C)
        iris.php -h=action (ou -h=A)
        iris.php -h=module (ou -h=M)
        iris.php -h=workbench (ou -h=W)
        iris.php -h=menuname (ou -h=N)
        iris.php -h=makemenu (ou -h=n)
        iris.php -h=makecore (ou -h=k)
        iris.php -h=searchcore (ou -h=K)
        iris.php -h=copyright (ou -h=o)
        iris.php -h=genericparameter (ou -h=G)
        iris.php -h=password (ou -h=w)
        iris.php -h=database (ou -h=B)
        iris.php -h=selectbase (ou -h=b)
        iris.php -h=makedbini (ou -h=I)
        iris.php -h=otherdb (ou -h=O)
        iris.php -h=entitygenerate (ou -h=e)
        iris.php -h=verbose (ou -h=v)
";
Messages::$Help['En']['H'] = Messages::$Help['Fr'] ['H'];
Messages::$Help['Ext']['H'] ='more';

Messages::$Help['En']['more'] = "
====================================================================================
To view this screen more comfortably, consider 
adding |more or |less after the help command
or using irishelp command_name (under linux)";

Messages::$Help['Fr']['more'] = "
====================================================================================    
Pour consulter cet écran plus confortablement, pensez 
à ajouter |more ou |less après la commande d'aide
ou à utiliser la commande irishelp nom_de_commande (sous linux)";

Messages::$Help['Ext']['more'] = "FALSE";
// ==========================================================================================================================================
// url
// ==========================================================================================================================================
Messages::$Help['En']['u'] = 
"iris.php --createproject /srv/new --url nouveau.local
iris.php --createproject /srv/new --u nouveau.local

This parameter is only used together with --createproject. It defines an URL for the site test (only in development context. It has no signification for the production site.

If no --url parameter is not specified, an implicit URL will be created

iris.php --createproject /srv/new

In that example, the URL will be 'new.local'.
";
Messages::$Help['Fr']['u'] = 
"iris.php --createproject /srv/new --url nouveau.local
iris.php --createproject /srv/new --u nouveau.local

Ce paramètre s''utilise uniquement en compagnie de la fonction --createproject.
Il spécifie l''URL qui sera utilisée pour les tests du site (en local). Ce paramètre
n''a aucun impact sur le site en production. 

Si ce paramètre n''est pas spécifié, le site sera implicitement construit sur le nom du dossier qui contient l''application.

iris.php --createproject /srv/new

Définira une url par défaut de 'new.local'.
";
Messages::$Help['Ext']['u'] ='FALSE';
// ==========================================================================================================================================
// database
// ==========================================================================================================================================
Messages::$Help['Fr']['B'] =
"Fonctions :
    iris.php -B LIST|CREATE|SHOW|INI
    iris.php --database LIST|CREATE|SHOW|INI

La première fonction ''LIST'' donne la liste des bases de données déjà définies
par le développeur (une même base peut être utilisée par plusieurs projets).

La fonction ''CREATE'' appelle un programme interactif qui permet de définir
les paramètres d''une nouvelle base de données. Attention, le programme IRIS
ne crée pas la base de données (dans le cas d''une base SQLite, il vérifie néanmoins
l''existence du fichier mentionné, mais ne le crée pas).

Exemple de création de base SQLite:

   jacques@naxos:srv$ iris.php -B create
   Database id (unique internal value): mynewdb
   Adapter name [sqlite] :
   Directory [/application/config/base/] :
   Database file [demo.sqlite] :
   Warning /srv/ojbm2/application/config/base/demo.sqlite does not exist.
   Database managed by config INI file [TRUE] :

Exemple de création d''une base MariaDB:

    jacques@naxos:srv$ iris.php -B create
    Database id (unique internal value): mynewmysqldb
    Adapter name [sqlite] : mysql
    Database name : dataname
    Host name [localhost] :
    User name : root
    Password (will be echoed) : 123456
    Database managed by config INI file [TRUE] :

La fonction ''SHOW'' montre les caractérisques de la base définie par défaut.

    jacques@naxos:/srv/www$ iris.php -H=B show
    Default database definition:
    ------------------------------------------------------------------------------------
    Project name  : srv_www_u3a
    Database adapter : mysql
    Host name : localhost
    Database : u3a
    User name : u3a
    Password : == defined in file (not listed for security reasion) ==
    Managed by INI file : YES



La function ''INI'' permet d'afficher toutes les caractérisques des bases déjà
définie par le développeur.

    jacques@naxos:/srv/www$ iris.php -H=B ini
    
    [iristest]
    adapter = \"mysql\"
    dbname = \"iristest\"
    hostname = \"localhost\"
    username = \"iristest\"
    password = \"pwiris\"
    maindb = 1

...

    [invoices]
    adapter = \"sqlite\"
    dbname = \"/application/config/base/invoice.sqlite\"
    maindb = 1



---------------------------------------------------------------------
Les aides suivantes se rapportent à la gestion des bases de données:
";
Messages::$Help['En']['B'] = 
"Functions :
    iris.php -B LIST|CREATE|SHOW|INI
    iris.php --database LIST|CREATE|SHOW|INI

The ''LIST'' function displays all the databases defined by the the developper (a database may be
used by various projects).

The ''CREATE'' function calls an interactive program which permits to define a new database parameters
donne la liste des bases de données déjà définies
par le développeur (une même base peut être utilisée par plusieurs projets).

La fonction ''CREATE'' appelle un programme interactif qui permet de définir
les paramètres d''une nouvelle base de données. Attention, le programme IRIS
ne crée pas la base de données (dans le cas d''une base SQLite, il vérifie néanmoins
l''existence du fichier mentionné, mais ne le crée pas).

Exemple de création de base SQLite:

   jacques@naxos:srv$ iris.php -B create
   Database id (unique internal value): mynewdb
   Adapter name [sqlite] :
   Directory [/application/config/base/] :
   Database file [demo.sqlite] :
   Warning /srv/ojbm2/application/config/base/demo.sqlite does not exist.
   Database managed by config INI file [TRUE] :

Exemple de création d''une base MariaDB:

    jacques@naxos:srv$ iris.php -B create
    Database id (unique internal value): mynewmysqldb
    Adapter name [sqlite] : mysql
    Database name : dataname
    Host name [localhost] :
    User name : root
    Password (will be echoed) : 123456
    Database managed by config INI file [TRUE] :

La fonction ''SHOW'' montre les caractérisques de la base définie par défaut.

    jacques@naxos:/srv/www$ iris.php -H=B show
    Default database definition:
    ------------------------------------------------------------------------------------
    Project name  : srv_www_u3a
    Database adapter : mysql
    Host name : localhost
    Database : u3a
    User name : u3a
    Password : == defined in file (not listed for security reasion) ==
    Managed by INI file : YES



La function ''INI'' permet d'afficher toutes les caractérisques des bases déjà
définie par le développeur.

    jacques@naxos:/srv/www$ iris.php -H=B ini
    
    [iristest]
    adapter = \"mysql\"
    dbname = \"iristest\"
    hostname = \"localhost\"
    username = \"iristest\"
    password = \"pwiris\"
    maindb = 1

...

    [invoices]
    adapter = \"sqlite\"
    dbname = \"/application/config/base/invoice.sqlite\"
    maindb = 1



---------------------------------------------------------------------
Les aides suivantes se rapportent à la gestion des bases de données:
";


Messages::$Help['Ext']['B'] ='DATABASE';

// ==========================================================================================================================================
// extension - DATABASE
// ==========================================================================================================================================
Messages::$Help['En']['DATABASE'] = 
"        iris.php -h=B  iris.php -h=database
        iris.php -h=b  iris.php -h=selectbase
        iris.php -h=I  iris.php -h=makedbini
        iris.php -h=e  iris.php --entitygenerate
";
Messages::$Help['Fr']['DATABASE'] = Messages::$Help['En']['DATABASE'];
Messages::$Help['Ext']['DATABASE'] ='more';

// ==========================================================================================================================================
// selectbase
// ==========================================================================================================================================
Messages::$Help['Fr']['b'] = 
"Fonction:
    iris.php -b databaseId
    iris.php --selectbase databaseID

Permet d''attribuer une base de données déjà définie au projet en cours.

   jacques@naxos:srv$ iris.php -B list
   List of known databases:
   ------------------------
   ojbm2     : /application/config/base/ojbm2.sqlite (sqlite)
   mynewdb   : /application/config/base/demo.sqlite (sqlite)
   mynewmysqldb: root@localhost:datanam (mysql)

   jacques@naxos:srv$ iris.php -s status
   -------------------------------------------------------------
   Status of srv_ojbm2
   -------------------------------------------------------------
   ProjectName : srv_ojbm2
   ...
   Database : ==NONE==
   ...
   Locked : 1

   jacques@naxos:srv$ iris.php --selectbase ojbm2
   Switching database from ==NONE== to ojbm2

   jacques@naxos:srv$ iris.php -s status
   -------------------------------------------------------------
   Status of srv_ojbm2
   -------------------------------------------------------------
   ProjectName : srv_ojbm2
   ...
   Database : ojbm2
   ...
   Locked : 1
        
---------------------------------------------------------------------
Les aides suivantes se rapportent à la gestion des bases de données:
";
Messages::$Help['En']['b'] = "";
Messages::$Help['Ext']['b'] ='DATABASE';

// ==========================================================================================================================================
// makedbini 
// ==========================================================================================================================================
Messages::$Help['En']['I'] = "";
Messages::$Help['Fr']['I'] = 
"Fonctions:        
    iris.php --makedbini        
    iris.php -I
        
Cette fonction permet de créer un fichier INI définissant les paramètres
par défaut de la base de données du projet. Elle est régie par les conditions
suivantes:
    - le projet doit avoir une base de données associée (voir --selectbase)
    - cette base doit avoir prévu la définition d''un fichier ini (voir --database CREATE
    - le fichier application/config/10_database.ini ne doit pas encore exister

jacques@naxos:~$ iris.php --makedbini
File /srv/nouveau/application/config/10_database.ini now contains all your settings.
        
---------------------------------------------------------------------
Les aides suivantes se rapportent à la gestion des bases de données:
";
Messages::$Help['Ext']['I'] ='DATABASE';

// ==========================================================================================================================================
//  mkcore
// ==========================================================================================================================================
Messages::$Help['En']['k'] = "";
Messages::$Help['Fr']['k'] = 
"Fonction :
    iris.php --mkcore class
    iris.php -k class

Prépare une classe à devenir modifiable par le développeur:
- copie et protection des éléments privés
- si nécessaire, création d''une classe dérivée (ne tentera pas de l''effacer si elle existe)
";
Messages::$Help['Ext']['k'] ='FALSE';

// ==========================================================================================================================================
// searchcore
// ==========================================================================================================================================
Messages::$Help['En']['K'] = "";
Messages::$Help['Fr']['K'] = 
"Fonction :
    iris.php --searchcore
    iris.php -K

Regénère le fichier ''config/overridden.classes'' en tenant compte des classes
effectivement surdéfinies par le développeur.

";
Messages::$Help['Ext']['K'] ='FALSE';
// ==========================================================================================================================================
// entitygenerate
// ==========================================================================================================================================
Messages::$Help['En']['e'] = "";
Messages::$Help['Fr']['e'] = 
"Cette fonction va générer les fichiers permettant la gestion automatisée
d''une table de base de données. Elle s''appuie sur le fichier  models/crud/CrudIconManager.php
copié lors de la définition initiale du projet (voir --createproject).
";
Messages::$Help['Ext']['e'] ='FALSE';

// ==========================================================================================================================================
// public
// ==========================================================================================================================================
Messages::$Help['En']['p'] = "";
Messages::$Help['Fr']['p'] = "";
Messages::$Help['Ext']['p'] ='FALSE';

// ==========================================================================================================================================
// application
// ==========================================================================================================================================
Messages::$Help['En']['a'] = "";
Messages::$Help['Fr']['a'] = "";
Messages::$Help['Ext']['a'] ='FALSE';

// ==========================================================================================================================================
// Module
// ==========================================================================================================================================
Messages::$Help['En']['M'] = 
""
        . "The --module option is often followed by -generate"
        . "\n"
        . "\n";
Messages::$Help['Fr']['M'] = 
""
        . "L'option --module est en général suivie de --generate"
        . "\n"
        . "\n";
Messages::$Help['Ext']['M'] ='g';

// ==========================================================================================================================================
// Controller
// ==========================================================================================================================================
Messages::$Help['En']['C'] = 
""
        . "The --controller option is often followed by -generate"
        . "\n"
        . "\n";
Messages::$Help['Fr']['C'] = 
""
        . "L'option --controller est en général suivie de --generate"
        . "\n"
        . "\n";
Messages::$Help['Ext']['C'] ='g';

// ==========================================================================================================================================
// Action
// ==========================================================================================================================================
Messages::$Help['En']['A'] = 
""
        . "The --action option is often followed by -generate"
        . "\n"
        . "\n";
Messages::$Help['Fr']['A'] = 
""
        . "L'option --action est en général suivie de --generate"
        . "\n"
        . "\n";

Messages::$Help['Ext']['A'] ='g';

// ==========================================================================================================================================
// generate
// ==========================================================================================================================================
Messages::$Help['En']['g'] = 
"The --generate option is used to generate an action method in the required controller and module. Default values may be stored for the default project.
    
It is advisable to specify them:
    iris.php --module NouveauModule --controller NouveauContrôleur --action NouvelleAction --generate
    iris.php -M NouveauModule -C NouveauContrôleur -A NouvelleAction -g
These parameters are stored. The following example shows the generation of three new actions:
    iris.php --module NouveauModule --controller NouveauContrôleur --action action1 --generate
    iris.php --action action2 --generate
    iris.php --action action3 --generate
";Messages::$Help['Fr']['g'] = 
"L'option --generate permet de générer une méthode d'action dans le contrôleur et le module prévu. Des valeurs par défaut sont
mémorisées pour le projet par défaut.
Il est conseillé de les spécifier:
    iris.php --module NouveauModule --controller NouveauContrôleur --action NouvelleAction --generate
    iris.php -M NouveauModule -C NouveauContrôleur -A NouvelleAction -g
Ces paramètres sont à leur tour mémorisés. Voici un exemple de génération de trois nouvelles actions:
    iris.php --module NouveauModule --controller NouveauContrôleur --action action1 --generate
    iris.php --action action2 --generate
    iris.php --action action3 --generate
";
Messages::$Help['Ext']['g'] ='FALSE';


// ==========================================================================================================================================
// language
// ==========================================================================================================================================
Messages::$Help['En']['1'] = 
"Change the interface language to French (Fr) or English (En) in the parameter file

iris.php --langage Fr

Change the language only during the other options execution (-F, --french, -E, --english) 

iris.php -F <other options> 
iris.php --english <other options>


";

Messages::$Help['Fr']['1'] = 
"Choisir le language de l''interface et de l''aide : français (Fr) ou anglais (En) et la mémoriser dans le fichier de paramètre

iris.php --langage Fr


Affiche des messages dans une autre langue, sans changer la valeur des fichiers de paramètres  (-F, --french, -E, --english) 

iris.php -F <other options> 
iris.php --english <other options>
";


Messages::$Help['Ext']['1'] ='FALSE';

Messages::$Help['Fr']['E'] = Messages::$Help['Fr']['1']; 
Messages::$Help['En']['E'] = Messages::$Help['En']['1']; 
Messages::$Help['Ext']['E'] ='FALSE';

Messages::$Help['En']['F'] = Messages::$Help['En']['1']; 
Messages::$Help['Fr']['F'] = Messages::$Help['Fr']['1']; 
Messages::$Help['Ext']['F'] ='FALSE';


// ==========================================================================================================================================
// defaultproject
// ==========================================================================================================================================
Messages::$Help['En']['d'] = 
"You can choose a default project among the defined project. This project will be detailled
by --show status and will be modified by --generate

     iris.php --setdefaultproject <project_name>
     iris.php -d <project_name>

";
Messages::$Help['Fr']['d'] = 
"Il est possible de choisir un projet par défaut. C'est lui qui sera détaillé par l'option 
--show status et qui sera modifié par --generate.        

     iris.php --setdefaultproject <project_name>
     iris.php -d <project_name>
     
";
Messages::$Help['Ext']['d'] ='FALSE';



// ==========================================================================================================================================
// unlock
// ==========================================================================================================================================
Messages::$Help['En']['U'] = "";
"A project may be 'unlocked' to permit its deletion by --removeproject

     iris.php --unlock <project_name>
     iris.php -U <project_name>

";
Messages::$Help['Fr']['U'] = 
"Un projet peut être 'déverrouillé' pour autoriser sa suppression par  --removeproject

     iris.php --unlock <project_name>
     iris.php -U <project_name>

";
Messages::$Help['Ext']['U'] ='FALSE';

// ==========================================================================================================================================
// lock
// ==========================================================================================================================================
Messages::$Help['En']['L'] = 
"A project may be 'locked' to prevent its deletion by --removeproject

     iris.php --lock <project_name>
     iris.php -L <project_name>
";
Messages::$Help['Fr']['L'] = 
"Un projet peut être 'verrouillé' pour interdire sa suppression par --removeproject

     iris.php --lock <project_name>
     iris.php -L <project_name>

";
Messages::$Help['Ext']['L'] ='FALSE';
