#! /usr/bin/env php
<?php
define('INIT', 1);
define('DESC', 2);
define('GPL', 3);
define('END', 4);

$fileName = $GLOBALS['argv'][1];
$file = file($fileName);
$newFile = '';
$modified = FALSE;
$state = INIT;
foreach ($file as $line) {
    if ($state == DESC) {
        if (preg_match('/This file is part of IRIS-PHP/', $line)) {
            echo "GPL message in class description\n";
            $modified = TRUE;
            $newFile .= "/*\n";
            $newFile .= $line;
            $state = GPL;
        }
        else {
            $classDescription[] = $line;
        }
    }
    elseif ($state == GPL) {
        $newFile .= $line;
        if (preg_match('/\*\//', $line)) {
            $state = END;
            $newFile .= "\n";
            $newFile .= implode("", $classDescription);
            $classDescription = [];
            $newFile .= $line;
        }
    }
    elseif ($state == INIT and preg_match('/\/\*\*/', $line)) {
        $classDescription[] = $line;
        $state = DESC;
    }
    else {
        $newFile .= $line;
    }
}
if ($modified) {
    echo "Writing modified file $fileName\n";
    $newFile .= implode("", $classDescription);
    file_put_contents($fileName, $newFile);
}

