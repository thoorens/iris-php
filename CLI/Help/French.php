<?php

namespace CLI\Help;

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
 * @copyright 2012 Jacques THOORENS
 *
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 *
 * @version $Id: $/**
 * The french version of Help providing all help messages
 *
 */

class French extends \CLI\_Help {

    protected function _general() {
        $userConfigDir = IRIS_USER_PARAMFOLDER;
        $script = 'iris.php';
        echo <<<HELP
iris.php
========
Ce programme offre une interface pour une série de commandes permettant la création
et la gestion d'un projet de site web à l'aide d'Iris-PHP.

Fonctions:

    iris.php /path/to/IRIS/installation/directory

Première invocation du programme et mémorisation du répertoire contenant une
version fonctionnelle du frameworks Iris-PHP. Ce chemin est mémorisé dans
le fichier de paramétrage (~$userConfigDir$script sous Linux)

    iris.php -h ou --help

HELP;
        echo "Pour une aide sur une fonction particulière tapez \n";
        $this->showFunctions();
    }

    protected function _createProject() {
        echo <<<CREATE

Fonction :
    iris.php --createproject PathToBaseDir  [--publicdir PublicName] [--applicationdir ApplicationName] [--url localURL]
    iris.php -c PathToBaseDir  [-p PublicName] [-a ApplicationName] [-u localURL]

Création d'un projet dans le répertoire PathToBaseDir. Le chemin peut être
absolu (/path/to/base/dir) ou relatif au répertoire courant (sub/dir).

La partie visible sera dans PublicName (par défaut 'public'),
la partie applicative dans ApplicationName (par défaut 'application')
et le site en developpement sera accessible par l'URL spécifiée (par défaut mysite.local).


CREATE;
    }

    protected function _alterProject() {
        echo "Fontion à documenter: --alterProject\n";
    }

    protected function _removeProject() {
        echo <<<REMOVE
Fonction :
    iris.php --removeproject PathToBaseDir confirm
    iris.php -r PathToBaseDir confirm

Supprime un projet en effaçant complètement le contenu du répertoire qui le contient. Attention cette opération
est destructrice et IRREVERSIBLE. C'est pour cette raison que le mot "confirm" doit être ajouté à la commande, afin
d'éviter les erreurs de manipulation. Sans ce paramètre, la commande se contente d'afficher les commandes qui devraient
s'exécuter (suppressions de fichiers, de liens et de dossiers) .

REMOVE;
    }

    protected function _url() {
        echo <<<URL
Ce paramètre s'utilise uniquement en compagnie de la fonction --createProject.
Il spécifie l'URL qui sera utilisée pour les tests du site (en local). Ce paramètre
n'a aucun impact sur le site en production. Il est simplement inscrit dans
Si ce paramètre n'est pas spécifié, le site sera implicitement "mysite.local".

URL;
    }

    protected function _show() {
        echo <<<SHOW
Fonction :
    iris.php --show list
    iris.php -s list

Affiche les projets de l'utilisateur courant.


Fonction :
    iris.php --show status
    iris.php -s status

Affiche à l'écran les parramètres actuels du projet par défaut
(c'est un bon moyen de vérifier lequel c'est).

Fonction :
    iris.php --show virtual
    iris.php - virtual

Reproduit le contenu du fichier destiné à paraméterer le
serveur Apache (utile en cas d'effacement du fichier xxx.virtual).

SHOW;
    }

    protected function _mkCore() {
        echo <<<CORE
Fonction :
    iris.php --mkcore class
    iris.php -k class

Prépare une classe à devenir modifiable par le développeur:
- copie et protection des éléments privés
- si nécessaire, création d'une classe dérivée (ne tentera pas de l'effacer si elle existe)


CORE;
    }

    protected function _searchCore() {
        echo <<<SCORE
 Fonction :
    iris.php --searchcore
    iris.php -K

Regénère le fichier 'config/overridden.classes' en tenant compte des classes
effectivement surdéfinies par le développeur.

SCORE;
    }

    protected function _default($command) {
        echo "Pas encore d'aide définie pour l'option $command\n";
    }

    public function error($number) {
        switch ($number) {

        }
    }

    protected function _database() {
        echo <<<DATABASE
Fonction :
    iris.php -B LIST|CREATE
    iris.php --database LIST|CREATE

La première fonction 'LISTE' donne la liste des bases de données déjà définies
par le développeur (une même base peut être utilisée par plusieurs projets).

La fonction 'CREATE' appelle un programme interactif qui permet de définir
les paramètres d'une nouvelle base de données. Attention, le programme IRIS
ne crée pas la base de données (dans le cas d'une base SQLite, il vérifie néanmoins
l'existence du fichier mentionné, mais ne le crée pas).

Exemple de création de base SQLite:

   jacques@naxos:srv$ iris.php -B create
   Database id (unique internal value): mynewdb
   Adapter name [sqlite] :
   Directory [/application/config/base/] :
   Database file [demo.sqlite] :
   Warning /srv/ojbm2/application/config/base/demo.sqlite does not exist.
   Database managed by config INI file [TRUE] :

Exemple de création d'une base MariaDB:

    jacques@naxos:srv$ iris.php -B create
    Database id (unique internal value): mynewmysqldb
    Adapter name [sqlite] : mysql
    Database name : dataname
    Host name [localhost] :
    User name : root
    Password (will be echoed) : 123456
    Database managed by config INI file [TRUE] :

Les aides suivantes se rapportent à la gestion des bases de données:
    iris.php -h=b
    iris.php -h=b
    iris.php -h=b
    iris.php -h=b
DATABASE;
    }

    protected function _makeDbIni() {

    }

    protected function _entityGenerate() {

    }

    protected function _otherDB() {

    }

    protected function _selectBase() {
        echo <<<SELECTBASE

Fonction:
    iris.php -b databaseId
    iris.php --selectbase databaseID

Permet d'attribuer une base de données déjà définie au projet en cours.

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

SELECTBASE;
    }

}

