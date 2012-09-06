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
        mkdir -p $TARGET
        cp -pr CLI $TARGET
        cp -pr Dojo $TARGET
        cp -pr ILO $TARGET
        cp -pr Iris $TARGET
        cp -pr IrsInternal $TARGET
        ln -s $PWD/CLI/iris.php /usr/local/bin/iris.php
    fi
fi
