<?php

// Test Loader for plain classes:
//\IrisTest\Engine\Loader::DoTest(1, array('index','transTest'));
\Iris\Translation\_Translator::SetCurrentTranslator(\Iris\Translation\SystemTranslator::GetInstance());

// In workbench, performances are, usually, not the main point
\Iris\MVC\Template::setCacheTemplate(\Iris\MVC\Template::CACHE_NEVER);

// Uncomment next to use local Dojo
//\Dojo\Manager::SetSource(0);
// To have an Admintoolbar without Ajax, uncomment the next line
//\ILO\views\helpers\AdminToolBar::$AjaxMode = \FALSE;

\Iris\Subhelpers\Head::GetInstance()->title('Site officiel d\'Iris-PHP');

// MD5 signature is an important feature of Work Bench
\Iris\SysConfig\Settings::EnableMD5Signature();