#! /usr/bin/env bash

# Only root may install Iris-PHP
if test ! $USER == "root"
then
    echo "You must be root to install Iris-PHP"
    exit 1
fi


if test "$1" == ""
then
    echo "You must provide a directory name in which install your framework library"
    exit 2
else
    if test -d $1/iris
    then 
        echo "The directory $1/iris seems to exist. Run again the install program with another target base directory"
        exit 3
    else
        TARGET=$1/iris
        echo "Creating directory $TARGET"
        mkdir -p $TARGET
        echo "Copying library folders..."
        echo "  -main library (Iris)"
        cp -pr Iris $TARGET
        echo "  -Iris LayOut resources (ILO)"  
        cp -pr ILO $TARGET
        echo "  -Iris Internal modules (IrisInternal)"
        cp -pr IrisInternal $TARGET
        echo "  -Iris Command Line Interpreter (CLI)"
        cp -pr CLI $TARGET
        echo "  -Dojo extensions (Dojo)"
        cp -pr Dojo $TARGET
        echo "  -Iris WorkBench (IrisWB)"
        cp -pr IrisWB $TARGET
        echo "  -Tutorial internal library (Tutorial)"
        cp -pr Tutorial $TARGET
        echo "  -Special folders Extensions and Core (for class customisation)"
        cp -pr Extensions $TARGET
        cp -pr Core $TARGET
        echo "Creating iris.php in /usr/local/bin"
        ln -s $TARGET/CLI/iris.php /usr/local/bin/iris.php
    fi
fi
