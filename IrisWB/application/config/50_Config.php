<?php
// Test Loader for plain classes:
//\IrisTest\Engine\Loader::DoTest(1, array('index','transTest'));
//\Iris\SysConfig\Settings::SetDefaultTranslator('\Iris\Translation\SystemTranslator');



// In workbench, performances are, usually, not the main point
\Iris\SysConfig\Settings::SetCacheTemplate(\Iris\MVC\Template::CACHE_NEVER);

// Uncomment next to use local Dojo
//\Dojo\Manager::SetSource(0);
// To have an Admintoolbar without Ajax, uncomment the next line
//\ILO\views\helpers\AdminToolBar::$AjaxMode = \FALSE;
// MD5 signature is an important feature of Work Bench
\Iris\SysConfig\Settings::EnableMD5Signature();


//\Iris\Errors\Settings::GetInstance()->setDefaultController('/errordemo/Error');
//echo "Error modified";
//\Iris\Errors\Settings::SetController('/errordemo/Error');
