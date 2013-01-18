#! /usr/bin/env bash

BASE="srv"
DRAWLINE="echo ======================================================="
$DRAWLINE
echo "Create three new project"
$DRAWLINE
iris.php -c /$BASE/project1 
iris.php -s status
iris.php -c /$BASE/project2 -a applikation -p publik -u test.local
iris.php -s status

$DRAWLINE
echo "Add modules, controllers and actions"
$DRAWLINE
iris.php -d /$BASE/project2
iris.php -M soleil -g
iris.php -C coucher -g
iris.php -A photo -g
ls -l /$BASE/project2/applikation/modules/soleil/views/scripts/coucher_photo.iview

$DRAWLINE
echo "Database"
$DRAWLINE


$DRAWLINE
echo "Delete the project"
$DRAWLINE

iris.php -U /$BASE/project2
iris.php -r "$BASE"_project2 confirm

iris.php -d "$BASE"_project1
iris.php -U /$BASE/project1
iris.php -r /$BASE/project1 confirm

iris.php -s list