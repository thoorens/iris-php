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
 * \Iris\Log::POS_FILE : in log file
 * \Iris\Log::POS_PAGE : at the end of the page
 * \Iris\Log::POS_AUTO : where it occurs (may spoil the layout)
 * \Iris\Log::POS_NONE : no message
*/
\Iris\Log::GetInstance()->setPosition(\Iris\Log::POS_NONE);

/*
 * Choose the type of message you want to see
 * by uncommenting the corresponding line (more than one flags are possible)
 */

/* loader messages (general classes) */
//\Iris\Log::AddDebugFlag(\Iris\Engine\Debug::LOADER);
/*                 (view classes) */
//\Iris\Log::AddDebugFlag(\Iris\Engine\Debug::VIEW);
/*                 (helper classes) */
//\Iris\Log::AddDebugFlag(\Iris\Engine\Debug::HELPER);
/* You can precise here what classes to trace when \Debug::LOADER is set */
//\Iris\Engine\Loader::AddTrace(['test']);

/* router messages */
//\Iris\Log::AddDebugFlag(\Iris\Engine\Debug::ROUTE);
//
/* database (SQL queries) messages */
//\Iris\Log::AddDebugFlag(\Iris\Engine\Debug::DB);

/* All messages (they are many) */
//\Iris\Log::AddDebugFlag(\Iris\Engine\Debug::ALL)

