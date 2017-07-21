<?php
/*
 * Put a comment marker // before "return" to enable the debugging facilities,
 * otherwise the rest of this file will be ignored
 */
Iris\SysConfig\Settings::$UserFields['id'] = 'UserId';
Iris\SysConfig\Settings::$UserFields['UserName'] = 'NomUtilisateur';
Iris\SysConfig\Settings::$UserFields['Role'] = 'Groupe';
Iris\SysConfig\Settings::$UserFields['Email'] = 'Courriel';
//Iris\Errors\Settings::ShowErrorOnProd('2017-03-01','18');
//\Iris\SysConfig\Settings::SetDefaultUserName('inconnu');

//i_d(Iris\SysConfig\Settings::$UserFields['id']);
//$date = \DateTime();
//iris_debug($date);
//not_survivedate('t', strtotime('11/01/2015')));
//$session = \Iris\Users\Identity::CreateInstance($identity);
//$identity = filter_input(INPUT_SESSION, 'identity');
//$identity = filter_var($_SESSION['identity']);
//i_d($identity);
//Iris\Engine\Security::VerifyEmail('jacques@thoorens.net');
//i_dnd(\Iris\Engine\Security::GetInt(128));
//i_d(\Iris\Engine\Security::GetInt('Abcppp'));
die('Debug');

return;

/*
 * uncomment next line to enable debugging possibilities in development (ignored in production)
 */
define('IRIS_DEBUG', TRUE);

/*
 * Choose a log display position between
 * \Iris\Engine\Log::POS_FILE : in log file
 * \Iris\Engine\Log::POS_PAGE : at the end of the page
 * \Iris\Engine\Log::POS_AUTO : where it occurs (may spoil the layout)
 * \Iris\Engine\Log::POS_NONE : no message
*/

//\Iris\Engine\Log::GetInstance()->setPosition(\Iris\Engine\Log::POS_FILE);
//\Iris\Engine\Log::GetInstance()->setPosition(\Iris\Engine\Log::POS_PAGE);
//\Iris\Engine\Log::GetInstance()->setPosition(\Iris\Engine\Log::POS_AUTO);
//\Iris\Engine\Log::GetInstance()->setPosition(\Iris\Engine\Log::POS_NONE);


\Iris\Engine\Log::GetInstance()->setPosition(\Iris\Engine\Log::POS_AUTO);



/*
 * Choose the type of message you want to see
 * by uncommenting the corresponding line (more than one flags are possible)
 */

/* loader messages (general classes) */
//\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::LOADER);

/* You can precise here what classes to trace when \Debug::LOADER is set */
//\Iris\Engine\Loader::AddTrace(['test']);

/* router messages */
//\\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::ROUTE);

/* database (SQL queries) messages */
//\\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::DB);

/*                 (view classes) */
//\\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::VIEW);

/*                 (helper classes) */
//\\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::HELPER);

/* ACL management */
//\\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::ACL);

/* FILE is presently not used */
//\\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::FILE);

/* changes in settings */
//\\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::SETTINGS);

/* All messages (they are many) */
//\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::ALL);

/* HTML 5 detection messages */
//Iris\Engine\Log::AddDebugFlag(Iris\Engine\Debug::HTML5);
