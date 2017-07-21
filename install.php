#! /usr/bin/env php
<?php

/**
 * 
 * @param type $var
 * @return type
 */
function winShellVar($var) {
    return str_replace("\n", "", shell_exec("echo %$var%"));
}

/**
 * Borrowed from http://secure.php.net/manual/fr/function.copy.php
 * Posted by promaty@gmail.com
 */
// removes files and non-empty directories
function rrmdir($dir) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                rrmdir("$dir/$file");
            }
        }
        rmdir($dir);
    }
    else if (file_exists($dir)) {
        unlink($dir);
    }
}

/**
 * copies files and non-empty directories
 * 
 * @param type $src
 * @param type $dst
 * @param boolean $windows
 */
function rcopy($src, $dst, $windows) {
    if (file_exists($dst)) {
        rrmdir($dst);
    }
    if (is_dir($src)) {
        mkdir($dst);
        $files = scandir($src);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                rcopy("$src/$file", "$dst/$file", $windows);
            }
        }
    }
    else if (file_exists($src)) {
        if ($windows) {
            copy($src, $dst);
        }
        else {
            system("cp -a $src $dst");
        }
    }
}

// end of borrowing

/**
 * 
 * @param type $windows
 * @param type $target
 */
function systemLinks($windows, $target) {
    if ($windows) {
        $system32 = winShellVar("systemroot");
        $system32 .= "\\system32";
        $wtarget = str_replace('/', '\\', $target);
        $cmd = "mklink $system32\\iris.php $wtarget\\CLI\\iris.php";
        shell_exec($cmd);
    }
    else {
        echo "Creating a symbolic link from $target/CLI/iris.php to /usr/local/bin\n";
        if (file_exists("/usr/local/bin/iris.php")) {
            unlink("/usr/local/bin/iris.php");
        }
        symlink("$target/CLI/iris.php", "/usr/local/bin/iris.php");
        echo "Creating irishelp in /usr/local/bin\n";
        if (file_exists("/usr/local/bin/irishelp")) {
            unlink("/usr/local/bin/irishelp");
        }
        symlink("$target/CLI/irishelp", "/usr/local/bin/irishelp");
    }
}

/* Only root may install Iris-PHP */

if (PHP_OS == 'Linux') {
    $windows = \FALSE;
}
elseif (PHP_OS == 'WINNT') {
    $windows = \TRUE;
}
else {
    echo "IRIS-PHP install works only in Windows 7,/8.x/10  and Linux, sorry!";
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
    $user = winShellVar('username');
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

if ($dirName == "") {
    echo ("You must provide a directory name in which install your framework library.\n");
    exit(2);
}
else {
    $target = "$dirName/iris";
    if (file_exists($target)) {
            echo "The directory $dirName/iris seems to exist. Run again the install program with another target base directory\n";
        if (!$windows) {
            echo "Do no forget to specify you are using MS Windows by adding '-w' to the command, if it is the case.\n";
        }
        exit(3);
    }
    else {
        echo "Creating directory $target\n";
        mkdir($target, 0777, \TRUE);
        echo "Copying library folders...\n";
        $folders = [
            "CLI" => "  -Iris Command Line Interpreter (CLI)",
            "Calendar" => "  -A library for Calendar extensions",
            "Core" => "  -Special folder Core (for class customisation)",
            "Extensions" => "  -Special folder Extensions (for class customisation)",
            "Dojo" => "  -Dojo extensions (Dojo)",
            "IrisWB" => "  -Iris WorkBench (IrisWB)",
            "Iris" => "  -main library (Iris)",
            "ILO" => "  -Iris LayOut resources (ILO)",
            "IrisInternal" => "  -Iris Internal modules (IrisInternal)",
            "JQuery" => "  -A library for JQuery extensions",
            "Payoff" => "  -Payoff library (Payoff)",
            "TextFormat" => "Conceived as an extension of Vendor\MarkDown",
            "Tutorial" => "  -Tutorial internal library (Tutorial)",
            "Vendors" => "composed of libraries written by other programmers",
        ];
        foreach ($folders as $name => $description) {
            echo $description . "\n";
            rcopy($name, "$target/$name", $windows);
        }
        $files = [
            'gpl-3.0.txt' => "A copy of the GNU general Licence",
        ];
        foreach ($files as $name => $description) {
            echo $description . "\n";
            copy($name, "$target/$name");
        }
        systemLinks($windows, $target);
    }
}
