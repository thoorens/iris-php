<?php
// ==========================================================================================================================================
// --help
// ==========================================================================================================================================

Messages::$help['En']['h'] =
'iris.php
========
This program offers an interface for various commands to create and manage
a site project realized with Iris-PHP

Functions:

    iris.php /path/to/IRIS/installation/directory

First invocation of the program and memorisation of the name of the folder
containing the framework libraries. This path is recorded in a parameter file
(/home/username/.iris/iris.ini  under Linux)

    iris.php -h ou --help

For an explicit help for a special function, type :
';

Messages::$help['Fr']['h'] =        
"iris.php
========
Ce programme offre une interface pour une série de commandes permettant la création
et la gestion d''un projet de site web à l'aide dIris-PHP.

Fonctions:

    iris.php /path/to/IRIS/installation/directory

Première invocation du programme et mémorisation du répertoire contenant une
version fonctionnelle du frameworks Iris-PHP. Ce chemin est mémorisé dans
le fichier de paramétrage (/home/username/.iris/iris.ini sous Linux)

    iris.php -h ou --help

Pour une aide sur une fonction particulière tapez :
";
Messages::$help['Ext']['h'] ='TRUE';


// ==========================================================================================================================================
// createproject
// ==========================================================================================================================================
Messages::$help['Fr']['c'] =
"Fonction :
    iris.php --createproject PathToBaseDir  [--publicdir PublicName] [--applicationdir ApplicationName] [--url localURL]
    iris.php -c PathToBaseDir  [-p PublicName] [-a ApplicationName] [-u localURL] 
 
Création d''un projet dans le répertoire PathToBaseDir. Le chemin doit être absolu (/path/to/base/dir).
 
La partie visible sera dans PublicName (par défaut ''public''),    
la partie applicative dans ApplicationName (par défaut ''application'')                         
et le site en developpement sera accessible par l''URL spécifiée (par défaut mysite.local).
";
Messages::$help['En']['c'] = 
"Function :
    iris.php --createproject PathToBaseDir  [--publicdir PublicName] [--applicationdir ApplicationName] [--url localURL]
    iris.php -c PathToBaseDir  [-p PublicName] [-a ApplicationName] [-u localURL] 
 
Creates a new project in the folder PathToBaseDir. The path has to be absolute (/path/to/base/dir).
 
The visible part will be in PublicName (by default ''public''),    
The application part in ApplicationName (by default ''application'')                         
and the development site will have a specific URL (by default mysite.local).
";
Messages::$help['Ext']['c'] ='FALSE';

// ==========================================================================================================================================
// removeproject
// ==========================================================================================================================================
Messages::$help['Fr']['r'] =
"Function :                                                                                                                                           
    iris.php --removeproject PathToBaseDir confirm                                                                                                                                        
    iris.php -r PathToBaseDir confirm                                                                                                                                                     
     
Supprime un projet en effaçant complètement le contenu du répertoire qui le contient. Attention cette opération                                                                           
est destructrice et IRREVERSIBLE. C''est pour cette raison que le mot 'confirm' doit être ajouté à la commande, afin                                                                      
d'éviter les erreurs de manipulation. Sans ce paramètre, la commande se contente d'afficher les commandes qui devraient                                                                 
s'exécuter (suppressions de fichiers, de liens et de dossiers) . 
";
Messages::$help['En']['r'] =
"Function :                                                                                                                                           
    iris.php --removeproject PathToBaseDir confirm                                                                                                                                        
    iris.php -r PathToBaseDir confirm                                                                                                                                                     
     
Deletes  a project completely by erasing the content of the directory containing it. Please note this operation
is very destructive. It is for this reason that the word 'confirm' should be added at then end of the line, in order to
confirm your intention. Without this parameter, the command only displays the commands that should
run (deletion of files, links and folders).
";
Messages::$help['Ext']['r'] ='FALSE';
// ==========================================================================================================================================
// show
// ==========================================================================================================================================
Messages::$help['En']['s'] =
"Fonction :                                                                                                                                           
    iris.php --show list   
    iris.php -s list
       
Displays the projects of the current user.  

Fonction :
    iris.php --show status
    iris.php -s status

Display the recorded parameters of the current default project (a good way to see which project is seen as the default project).

Fonction :
    iris.php --show ini
    iris.php - s ini

Display the file containing all the project parameters  (/home/usrname/.iris/projects.ini)

Fonction :
    iris.php --show virtual
    iris.php -s  virtual

Displays what Apache should receive as parameters to recognize the project as a virtual server.','Fonction :                                                                                                                                           
    iris.php --show list   
    iris.php -s list
";
Messages::$help['Fr']['s'] =
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
Messages::$help['Ext']['s'] ='FALSE';
// ==========================================================================================================================================
// verbose
// ==========================================================================================================================================
Messages::$help['Fr']['v'] =
"Lorsque le paramètre -v ou --verbose intervient, certains messages explicites sont 
ajoutés à l''exécution du programme

      iris.php -v -c
ou
     iris.php --verbose --createproject

";
Messages::$help['En']['v'] = "";
Messages::$help['Ext']['v'] ='FALSE';
// ==========================================================================================================================================
// help
// ==========================================================================================================================================
Messages::$help['Fr']['h'] =
"iris.php -h=help: (ou -h=h:)
        iris.php -h=show (ou -h=s)
        iris.php -h=language (ou -h=1)
        iris.php -h=test (ou -h=t)
        iris.php -h=createproject (ou -h=c)
        iris.php -h=removeproject (ou -h=r)
        iris.php -h=forceproject (ou -h=f)
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
Messages::$help['En']['h'] = Messages::$help['Fr'] ['h'];
Messages::$help['Ext']['h2'] ='FALSE';
// ==========================================================================================================================================
// url
// ==========================================================================================================================================
Messages::$help['En']['u'] = 
"iris.php --createproject /srv/new --url nouveau.local
iris.php --createproject /srv/new --u nouveau.local

This parameter is only used together with --createproject. It defines an URL for the site test (only in development context. It has no signification for the production site.

If no --url parameter is not specified, an implicit URL will be created

iris.php --createproject /srv/new

In that example, the URL will be 'new.local'.
";
Messages::$help['Fr']['u'] = 
"iris.php --createproject /srv/new --url nouveau.local
iris.php --createproject /srv/new --u nouveau.local

Ce paramètre s''utilise uniquement en compagnie de la fonction --createproject.
Il spécifie l''URL qui sera utilisée pour les tests du site (en local). Ce paramètre
n''a aucun impact sur le site en production. 

Si ce paramètre n''est pas spécifié, le site sera implicitement construit sur le nom du dossier qui contient l''application.

iris.php --createproject /srv/new

Définira une url par défaut de 'new.local'.
";
Messages::$help['Ext']['u'] ='FALSE';
// ==========================================================================================================================================
// database
// ==========================================================================================================================================
Messages::$help['Fr']['B'] =
"Fonctions :
    iris.php -B LIST|CREATE
    iris.php --database LIST|CREATE

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

---------------------------------------------------------------------
Les aides suivantes se rapportent à la gestion des bases de données:
";
Messages::$help['En']['B'] = "";
Messages::$help['Ext']['B'] ='DATABASE';

// ==========================================================================================================================================
// DATABASE
// ==========================================================================================================================================
Messages::$help['En']['DATABASE'] = 
"       iris.php -h=B  iris.php -h=database
        iris.php -h=b  iris.php -h=selectbase
        iris.php -h=I  iris.php -h=makedbini
        iris.php -h=e  iris.php --entitygenerate
";
Messages::$help['Fr']['DATABASE'] = Messages::$help['En']['DATABASE'];
Messages::$help['Ext']['DATABASE'] ='FALSE';

// ==========================================================================================================================================
// selectbase
// ==========================================================================================================================================
Messages::$help['Fr']['b'] = 
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
Messages::$help['En']['b'] = "";
Messages::$help['Ext']['b'] ='DATABASE';

// ==========================================================================================================================================
// makedbini 
// ==========================================================================================================================================
Messages::$help['En']['I'] = "";
Messages::$help['Fr']['I'] = 
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
Messages::$help['Ext']['I'] ='DATABASE';

// ==========================================================================================================================================
//  mkcore
// ==========================================================================================================================================
Messages::$help['En']['k'] = "";
Messages::$help['Fr']['k'] = 
"Fonction :
    iris.php --mkcore class
    iris.php -k class

Prépare une classe à devenir modifiable par le développeur:
- copie et protection des éléments privés
- si nécessaire, création d''une classe dérivée (ne tentera pas de l''effacer si elle existe)
";
Messages::$help['Ext']['k'] ='FALSE';

// ==========================================================================================================================================
// searchcore
// ==========================================================================================================================================
Messages::$help['En']['K'] = "";
Messages::$help['Fr']['K'] = 
"Fonction :
    iris.php --searchcore
    iris.php -K

Regénère le fichier ''config/overridden.classes'' en tenant compte des classes
effectivement surdéfinies par le développeur.

";
Messages::$help['Ext']['K'] ='FALSE';
// ==========================================================================================================================================
// entitygenerate
// ==========================================================================================================================================
Messages::$help['En']['e'] = "";
Messages::$help['Fr']['e'] = 
"Cette fonction va générer les fichiers permettant la gestion automatisée
d''une table de base de données. Elle s''appuie sur le fichier  models/crud/CrudIconManager.php
copié lors de la définition initiale du projet (voir --createproject).
";
Messages::$help['Ext']['e'] ='FALSE';

// ==========================================================================================================================================
// public
// ==========================================================================================================================================
Messages::$help['En']['p'] = "";
Messages::$help['Fr']['p'] = "";
Messages::$help['Ext']['p'] ='FALSE';

// ==========================================================================================================================================
// application
// ==========================================================================================================================================
Messages::$help['En']['a'] = "";
Messages::$help['Fr']['a'] = "";
Messages::$help['Ext']['a'] ='FALSE';

// ==========================================================================================================================================
// Module
// ==========================================================================================================================================
Messages::$help['En']['M'] = 
""
        . "The --module option is often followed by -generate"
        . "\n"
        . "\n";
Messages::$help['Fr']['M'] = 
""
        . "L'option --module est en général suivie de --generate"
        . "\n"
        . "\n";
Messages::$help['Ext']['M'] ='g';

// ==========================================================================================================================================
// Controller
// ==========================================================================================================================================
Messages::$help['En']['C'] = 
""
        . "The --controller option is often followed by -generate"
        . "\n"
        . "\n";
Messages::$help['Fr']['C'] = 
""
        . "L'option --controller est en général suivie de --generate"
        . "\n"
        . "\n";
Messages::$help['Ext']['C'] ='g';

// ==========================================================================================================================================
// Action
// ==========================================================================================================================================
Messages::$help['En']['A'] = 
""
        . "The --action option is often followed by -generate"
        . "\n"
        . "\n";
Messages::$help['Fr']['A'] = 
""
        . "L'option --action est en général suivie de --generate"
        . "\n"
        . "\n";

Messages::$help['Ext']['A'] ='g';

// ==========================================================================================================================================
// Action
// ==========================================================================================================================================
Messages::$help['En']['g'] = 
"The --generate option is used to generate an action method in the required controller and module. Default values may be stored for the default project.
    
It is advisable to specify them:
    iris.php --module NouveauModule --controller NouveauContrôleur --action NouvelleAction --generate
    iris.php -M NouveauModule -C NouveauContrôleur -A NouvelleAction -g
These parameters are stored. The following example shows the generation of three new actions:
    iris.php --module NouveauModule --controller NouveauContrôleur --action action1 --generate
    iris.php --action action2 --generate
    iris.php --action action3 --generate
";Messages::$help['Fr']['g'] = 
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
Messages::$help['Ext']['g'] ='FALSE';


// ==========================================================================================================================================
// language
// ==========================================================================================================================================
Messages::$help['En']['1'] = 
"Change the interface language to French (Fr) or English (En) in the parameter file

iris.php --langage Fr

Change the language only during the other options execution (-F, --french, -E, --english) 

iris.php -F <other options> 
iris.php --english <other options>


";
Messages::$help['En']['F'] = Messages::$help['En']['1']; 
Messages::$help['En']['E'] = Messages::$help['En']['1']; 

Messages::$help['Fr']['1'] = 
"Choisir le language de l''interface et de l''aide : français (Fr) ou anglais (En) et la mémoriser dans le fichier de paramètre

iris.php --langage Fr


Affiche des messages dans une autre langue, sans changer la valeur des fichiers de paramètres  (-F, --french, -E, --english) 

iris.php -F <other options> 
iris.php --english <other options>
";
Messages::$help['Fr']['F'] = Messages::$help['Fr']['1']; 
Messages::$help['Fr']['E'] = Messages::$help['Fr']['1']; 


Messages::$help['Ext']['1'] ='FALSE';

// ==========================================================================================================================================
// defaultproject
// ==========================================================================================================================================
Messages::$help['En']['d'] = 
"You can choose a default project among the defined project. This project will be detailled
by --show status and will be modified by --generate

     iris.php --setdefaultproject <project_name>
     iris.php -d <project_name>

";
Messages::$help['Fr']['d'] = 
"Il est possible de choisir un projet par défaut. C'est lui qui sera détaillé par l'option 
--show status et qui sera modifié par --generate.        

     iris.php --setdefaultproject <project_name>
     iris.php -d <project_name>
     
";
Messages::$help['Ext']['d'] ='FALSE';



// ==========================================================================================================================================
// unlock
// ==========================================================================================================================================
Messages::$help['En']['U'] = "";
"A project may be 'unlocked' to permit its deletion by --removeproject

     iris.php --unlock <project_name>
     iris.php -U <project_name>

";
Messages::$help['Fr']['U'] = 
"Un projet peut être 'déverrouillé' pour autoriser sa suppression par  --removeproject

     iris.php --unlock <project_name>
     iris.php -U <project_name>

";
Messages::$help['Ext']['U'] ='FALSE';

// ==========================================================================================================================================
// lock
// ==========================================================================================================================================
Messages::$help['En']['L'] = 
"A project may be 'locked' to prevent its deletion by --removeproject

     iris.php --lock <project_name>
     iris.php -L <project_name>
";
Messages::$help['Fr']['L'] = 
"Un projet peut être 'verrouillé' pour interdire sa suppression par --removeproject

     iris.php --lock <project_name>
     iris.php -L <project_name>

";
Messages::$help['Ext']['L'] ='FALSE';
