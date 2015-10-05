<?php
// Test Loader for plain classes:
//\IrisTest\Engine\Loader::DoTest(1, array('index','transTest'));
//\Iris\SysConfig\Settings::$DefaultTranslator = '\Iris\Translation\SystemTranslator';



// In workbench, performances are, usually, not the main point
\Iris\SysConfig\Settings::$CacheTemplate = \Iris\MVC\Template::CACHE_NEVER;

// Uncomment next to use local Dojo
//\Dojo\Settings::$Source = Dojo\Manager::LOCAL;
// To have an Admintoolbar without Ajax, uncomment the next line
\ILO\views\helpers\AdminToolBar::$AjaxMode = \FALSE;
// MD5 signature is an important feature of Work Bench
\Iris\SysConfig\Settings::$MD5Signature = \TRUE;


//\Iris\Errors\Settings::$Controller = '/errordemo/Error';
//echo "Error modified";
//\Iris\Errors\Settings::$Controller ='/errordemo/Error';
