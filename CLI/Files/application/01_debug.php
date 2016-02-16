<?php

/*
 * Put a comment marker // before "return" to enable the debugging facilities,
 * otherwise the rest of this file will be ignored.
 * 
 * At the end of development, you can even delete this file.
 */
return;

/*
 * uncomment next line to enable debugging possibilities in development (ignored in production)
 */
//define('IRIS_DEBUG', TRUE);

/*
 * Choose a log display position between
 * \Iris\Engine\Log::POS_FILE : in log file
 * \Iris\Engine\Log::POS_PAGE : at the end of the page
 * \Iris\Engine\Log::POS_AUTO : where it occurs (may spoil the layout)
 * \Iris\Engine\Log::POS_NONE : no message
*/
\Iris\Engine\Log::GetInstance()->setPosition(\Iris\Engine\Log::POS_NONE);

/*
 * Choose the type of message you want to see
 * by uncommenting the corresponding line (more than one flags are possible)
 */

/* loader messages (general classes) */
//\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::LOADER);
/*                 (view classes) */
//\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::VIEW);
/*                 (helper classes) */
//\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::HELPER);
/* You can precise here what classes to trace when \Debug::LOADER is set */
//\Iris\Engine\Loader::AddTrace(['test']);

/* router messages */
//\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::ROUTE);
//
/* database (SQL queries) messages */
//\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::DB);

/* All messages (they are many) */
//\Iris\Engine\Log::AddDebugFlag(\Iris\Engine\Debug::ALL)


