DROP TABLE IF EXISTS "help";
CREATE TABLE "help"(id TEXT,En TEXT,Fr TEXT,Ext BOOL DEFAULT FALSE);
INSERT INTO "help" VALUES('h','iris.php
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
','iris.php
========
Ce programme offre une interface pour une série de commandes permettant la création
et la gestion d''un projet de site web à l''aide d''Iris-PHP.

Fonctions:

    iris.php /path/to/IRIS/installation/directory

Première invocation du programme et mémorisation du répertoire contenant une
version fonctionnelle du frameworks Iris-PHP. Ce chemin est mémorisé dans
le fichier de paramétrage (/home/username/.iris/iris.ini sous Linux)

    iris.php -h ou --help

Pour une aide sur une fonction particulière tapez :
','TRUE');
INSERT INTO "help" VALUES('c','','Fonction :
    iris.php --createproject PathToBaseDir  [--publicdir PublicName] [--applicationdir ApplicationName] [--url localURL]
    iris.php -c PathToBaseDir  [-p PublicName] [-a ApplicationName] [-u localURL] 
 
Création d''un projet dans le répertoire PathToBaseDir. Le chemin peut être  
absolu (/path/to/base/dir) ou relatif au répertoire courant  (sub/dir).
 
La partie visible sera dans PublicName (par défaut ''public''),    
la partie applicative dans ApplicationName (par défaut ''application'')                         
et le site en developpement sera accessible par l''URL spécifiée (par défaut mysite.local).
','FALSE');
INSERT INTO "help" VALUES('r',NULL,'Fonction :                                                                                                                                           
    iris.php --removeproject PathToBaseDir confirm                                                                                                                                        
    iris.php -r PathToBaseDir confirm                                                                                                                                                     
                                                                                                                                                                                          
Supprime un projet en effaçant complètement le contenu du répertoire qui le contient. Attention cette opération                                                                           
est destructrice et IRREVERSIBLE. C''est pour cette raison que le mot "confirm" doit être ajouté à la commande, afin                                                                      
d''éviter les erreurs de manipulation. Sans ce paramètre, la commande se contente d''afficher les commandes qui devraient                                                                 
s''exécuter (suppressions de fichiers, de liens et de dossiers) .                                                                                                                         
                                                                                                                                                                                          
','FALSE');
INSERT INTO "help" VALUES('s',NULL,'Fonction :                                                                                                                                           
    iris.php --show list                                                                                                                                                                  
    iris.php -s list                                                                                                                                                                      
                                                                                                                                                                                          
Affiche les projets de l''utilisateur courant.                                                                                                                                            
                                                                                                                                                                                          
                                                                                                                                                                                          
Fonction :
    iris.php --show status
    iris.php -s status

Affiche à l''écran les parramètres actuels du projet par défaut
(c''est un bon moyen de vérifier lequel c''est).

Fonction :
    iris.php --show ini
    iris.php - s ini

Affiche à l''écran le contenu entier du fichier de paramètres des projets
 (/home/usrname/.iris/projects.ini

Fonction :
    iris.php --show virtual
    iris.php -s  virtual

Reproduit le contenu du fichier destiné à paraméterer le
serveur Apache (utile en cas d''effacement du fichier xxx.virtual).
','FALSE');
INSERT INTO "help" VALUES('v','','Lorsque le paramètre -v ou --verbose intervient, certains messages explicites sont 
ajoutés à l''exécution du programme

      iris.php -v -c
ou
     iris.php --verbose --createproject

','FALSE');
INSERT INTO "help" VALUES('h2','        iris.php -h=help: (ou -h=h:)
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
',NULL,'FALSE');
INSERT INTO "help" VALUES('u',NULL,'Ce paramètre s''utilise uniquement en compagnie de la fonction --createProject.
Il spécifie l''URL qui sera utilisée pour les tests du site (en local). Ce paramètre
n''a aucun impact sur le site en production. Il est simplement inscrit dans
Si ce paramètre n''est pas spécifié, le site sera implicitement construit sur le nom du dossier qui contient l''application.
','FALSE');
INSERT INTO "help" VALUES('B',NULL,'Fonctions :
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
','DATABASE');
INSERT INTO "help" VALUES('DATABASE','        iris.php -h=B  iris.php -h=database
        iris.php -h=b  iris.php -h=selectbase
        iris.php -h=I  iris.php -h=makedbini
        iris.php -h=e  iris.php --entitygenerate
',NULL,'FALSE');
INSERT INTO "help" VALUES('b',NULL,'Fonction:
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

','DATABASE');
INSERT INTO "help" VALUES('I',NULL,'Fonctions:        
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
Les aides suivantes se rapportent à la gestion des bases de données:','DATABASE');
INSERT INTO "help" VALUES('k',NULL,'Fonction :
    iris.php --mkcore class
    iris.php -k class

Prépare une classe à devenir modifiable par le développeur:
- copie et protection des éléments privés
- si nécessaire, création d''une classe dérivée (ne tentera pas de l''effacer si elle existe)
','FALSE');
INSERT INTO "help" VALUES('K',NULL,' Fonction :
    iris.php --searchcore
    iris.php -K

Regénère le fichier ''config/overridden.classes'' en tenant compte des classes
effectivement surdéfinies par le développeur.

','FALSE');
INSERT INTO "help" VALUES('e',NULL,'Cette fonction va générer les fichiers permettant la gestion automatisée
d''une table de base de données. Elle s''appuie sur le fichier  models/crud/CrudIconManager.php
copié lors de la définition initiale du projet (voir --createproject).','FALSE');
INSERT INTO "help" VALUES('p',NULL,NULL,'FALSE');
INSERT INTO "help" VALUES('a',NULL,NULL,'FALSE');
INSERT INTO "help" VALUES('M',NULL,NULL,'FALSE');
INSERT INTO "help" VALUES('C',NULL,NULL,'FALSE');
INSERT INTO "help" VALUES('A',NULL,NULL,'FALSE');
