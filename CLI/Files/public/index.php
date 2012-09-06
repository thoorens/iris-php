<?php

require 'Bootstrap.php';
$bootstrap = new \Iris\Engine\Bootstrap();
$program = $bootstrap->newProgram("{APPLICATION}");
$program->run();
