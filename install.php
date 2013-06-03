#! /usr/bin/env php
<?php

function makedir($dir) {
    //echo "Making dir $dir\n";
    mkdir($dir);
}

function kopy($src, $dst) {
    //echo "Copying $src to $dst\n";
    copy($src, $dst);
}

function removedir($dir) {
    //echo "Removing $dir\n";
    rmdir($dir);
}

function erase($file) {
    //echo "Erasing $file\n";
    unlink($file);
}

function shellvar($var) {
    return str_replace("\n", "", shell_exec("echo %$var%"));
}

/**
 * Borrowed from http://php.net/manual/fr/function.rcopy.php
 */
// removes files and non-empty directories
function rrmdir($dir) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file)
            if ($file != "." && $file != "..")
                rrmdir("$dir/$file");
        removedir($dir);
    }
    else if (file_exists($dir))
        erase($dir);
}

// copies files and non-empty directories
function rcopy($src, $dst) {
    if (file_exists($dst))
        rrmdir($dst);
    if (is_dir($src)) {
        makedir($dst);
        $files = scandir($src);
        foreach ($files as $file)
            if ($file != "." && $file != "..")
                rcopy("$src/$file", "$dst/$file");
    }
    else if (file_exists($src))
        kopy($src, $dst);
}

// end of borrowing



/* Only root may install Iris-PHP */

if (PHP_OS == 'Linux')
    $windows = \FALSE;
elseif (PHP_OS == 'WINNT') {
    $windows = \TRUE;
}
else {
    echo "IRIS-PHP install works only in Windows 7 and Linux, sorry!";
    exit(100);
}
$dirName = "";

switch ($argc) {
    case 1:
        die("You must provide a directory name in which install your framework library.\n");
        break;
    case 2:
        $dirName = $argv[1];
        break;
}

if ($windows) {
    $user = shellvar('username');
    echo ("Hello, $user, we hope you have administrator power to install Iris-PHP.\n");
}
else {
    /* Only root may install Iris-PHP */
    $processUser = posix_getpwuid(posix_geteuid());
    $user = $processUser['name'];
    if ($user != "root"):
        echo ("Sorry, $user, you must be root to install Iris-PHP.\n");
        exit(1);
    endif;
}

if ($dirName == ""):
    echo ("You must provide a directory name in which install your framework library.\n");
    exit(2);
else:
    if (file_exists("$dirName/iris")):
        echo "The directory $dirName/iris seems to exist. Run again the install program with another target base directory\n";
        if (!$windows) {
            echo "Do no forget to specify you are using MS Windows by adding '-w' to the command, if it is the case.\n";
        }
        exit(3);
    else:
        $target = "$dirName/iris";
        echo "Creating directory $target\n";
        mkdir($target, 0777, \TRUE);
        echo "Copying library folders...\n";
        echo "  -main library (Iris)\n";
        rcopy('Iris', "$target/Iris");
        echo "  -Iris LayOut resources (ILO)\n";
        rcopy('ILO', "$target/ILO");
        echo "  -Iris Internal modules (IrisInternal)\n";
        rcopy('IrisInternal', "$target/IrisInternal");
        echo "  -Iris Command Line Interpreter (CLI)\n";
        rcopy('CLI', "$target/CLI");
        echo "  -Dojo extensions (Dojo)\n";
        rcopy('Dojo', "$target/Dojo");
        echo "  -Iris WorkBench (IrisWB)\n";
        rcopy('IrisWB', "$target/IrisWB");
        echo "  -Tutorial internal library (Tutorial)\n";
        rcopy('Tutorial', "$target/Tutorial");
        echo "  -Payoff library (Tutorial)\n";
        rcopy('Tutorial', "$target/Payoff");
        echo "  -Special folders Extensions and Core (for class customisation)\n";
        rcopy('Extensions', "$target/Extensions");
        rcopy('Core', "$target/Core");
        if ($windows) {
            $system32 = shellvar("systemroot");
            $system32 .= "\\system32";
            $wtarget = str_replace('/', '\\', $target);
            $cmd = "mklink $system32\iris.php $wtarget\\CLI\iris.php";
            shell_exec($cmd);
        }
        else {
            echo "Creating iris.php in /usr/local/bin\n";
            link("$target/CLI/iris.php", "/usr/local/bin/iris.php");
        }
    endif;
endif;
