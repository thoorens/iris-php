DROP TABLE IF EXISTS "error";
CREATE TABLE "error" ("id" INTEGER PRIMARY KEY  NOT NULL , "En" VARCHAR, "Fr" VARCHAR);
INSERT INTO "error" VALUES(0,'Your param file does not seem to be a valid one. Please check your configuration according to the manual instructions.\n','Votre fichier de paramètres semble incorrect. Veuillez vérifier votre configuration en fonction des instructions du manuel. \n');
INSERT INTO "error" VALUES(1,'iris.php is not able to find where to read or write your parameter files.
IRIS PHP CLI will not be functional on your system, sorry.','iris.php n''est pas en mesure de lire ou écrire vos fichiers de paramètres. IRIS PHP CLI ne sera pas en mesure de fonctionner sur votre système. Désolé.');
INSERT INTO "error" VALUES(2,'Your parameter file has been created.
Now you can use this program ("iris.php --help" for help).','Votre fichier de paramètres a été créé.
Vous pouvez maintenant utiliser ce programme normalement (iris.php --help pour de l''aide)');
INSERT INTO "error" VALUES(3,'The suggested path does not seem to be a valid Iris-PHP installation directory.','Le chemin que vous avez indiqué ne semble pas être un répertoire d''une installation valide d''IRIS-PHP');
INSERT INTO "error" VALUES(4,'You must supply the path to your Iris-PHP installation to init your parameter file
before beeing able to use this program. See documentation if necessary.','Vous devez fournir le chemin de votre installation d''IRIS-PHP pour intitaliser votre fichier de paramètres');
INSERT INTO "error" VALUES(5,'There seems to exist no projects.ini file. First create a project:
iris.php --createproject /path/to/the/new/project','Il ne semble pas exister de fichier projects.ini. Créez au préalable un projet:
iris.php --createproject /path/to/the/new/project');
INSERT INTO "error" VALUES(6,'No database has been defined by the current user (no /home/user/.iris/db.ini).','Aucune base de données n''a été définie par l''utilisateur courant (pas de /home/user/.iris/db.ini).');
INSERT INTO "error" VALUES(7,'You seem to have no more default project in your environment.
Select one with ''iris.php --setdefaultproject'' or create one with ''iris.php --createproject''.
','Il semble que vous n''ayez plus de projet par défaut dans votre environnement.
Choisissez-en un avec  ''iris.php --setdefaultproject'' ou créez-en un avec''iris.php --createproject''.');
INSERT INTO "error" VALUES(8,'The program tried to read a file with an incorrect name.','Le programme a tenté la lecture d''un fichier avec un nom incorrect');
INSERT INTO "error" VALUES(9,'The path to the project must be absolue, e. g.  /srv/projectdir','Le chemin vers le projet doit être absolu, ex. /srv/projectdir');
INSERT INTO "error" VALUES(10,'--module or -M  is not allowed in this context','--module or -M  n''est pas autorisé dans ce contexte');
INSERT INTO "error" VALUES(11,'--controller or -C is not allowed in this context','--controller or -C  n''st pas autorisé dans ce contexte');
INSERT INTO "error" VALUES(12,'--action or -A is not allowed in this context','--action or -A is n''est pas autorisé dans ce contexte');
INSERT INTO "error" VALUES(13,'--entitygenerate or -e is not allowed in this context','--entitygenerate or -e n''est pas autorisé dans ce contexte');
INSERT INTO "error" VALUES(14,'--makedbini or -I is not allowed in this context','--makedbini or -I n''est pas autorisé dans ce contexte');
INSERT INTO "error" VALUES(15,'--selectbase or -b is not allowed in this context','--selectbase or -b is n''est pas autorisé dans ce contexte');
INSERT INTO "error" VALUES(16,'--password must be a unique option followed by the password characters','--password doit être une option unique suivie par la suite de caractères du mot de passe');
INSERT INTO "error" VALUES(17,'--show must be a unique option followed by its command value (list, status, ini)','--show est une option unique suivie d''une commande (list, status, ini)');
INSERT INTO "error" VALUES(18,'--applicationdir may only come after the --createproject option','--applicationdir peut seulement venir  après une option --createproject');
INSERT INTO "error" VALUES(19,'--librarydir may only come after the --createproject option','--librarydir peut seulement venir après l''option --createproject');
INSERT INTO "error" VALUES(20,'--publicdir may only come after the --createproject option','--publicdir doit venir après une option  --createproject');
INSERT INTO "error" VALUES(21,'--url may only come after the --createproject option','--url peut seulement venir après l''option --createproject');
INSERT INTO "error" VALUES(22,'--%s cannot be used after another option (except --verbose)','--%s ne peut venir après une autre option (à l''exception de --verbose)');
INSERT INTO "error" VALUES(23,'--%s cannot be used after another option (except --module, --controller or --action)','--%s ne peut venir après une autre option (except --module, --controller or --action)');
INSERT INTO "error" VALUES(24,'The project named %s does not exist','Le project nommé %s n''existe pas');
INSERT INTO "error" VALUES(25,'The project %s does not exist and cannot be deleted.','Le projet %s n''existe pas et ne peut donc être effacé.');
INSERT INTO "error" VALUES(26,'Caution : the project  %s is locked. You may wish to use --unlockproject.','Attention : le projet %s est protégé. Vous devriez utiliser d''abord  --unlockproject.');
INSERT INTO "error" VALUES(27,'You must terminate the command removeproject by the word "confirm" to actually delete the project.','Vous devez terminer la commande --removeproject par le mot "confirm" pour procéder à l''effacement réel du projet.');
INSERT INTO "error" VALUES(28,'The project %s has been completely removed.','Le projet %s a été complètement effacé.');
