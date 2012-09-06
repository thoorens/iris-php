#! /usr/bin/env php
<?php
/*
 * Replace /* @version xyz by * @version $id: $
 */

$fileName = $argv[1];
if (basename($fileName) != 'untag.php') {
    $file = file($fileName);
    if (count($file) > 0) {
        $newFile = '';
        $modified = FALSE;
        foreach ($file as $line) {
        if (preg_match('/\* @version/', $line)) {
            $modified = TRUE;
            $newFile .= ' * @version $Id: $';
        }
            else {
                $newFile .= $line;
            }
        }
        if ($modified) {
            file_put_contents($fileName, $newFile);
        }
    }
}
