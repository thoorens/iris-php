<?php
// Test Loader for plain classes:
//\IrisTest\Engine\Loader::DoTest(1, array('index','transTest'));
\Iris\Translation\_Translator::SetCurrentTranslator(\Iris\Translation\SystemTranslator::GetInstance());

// In workbench, performances are, usually, not the main point
\Iris\MVC\Template::setCacheTemplate(\Iris\MVC\Template::CACHE_NEVER);
