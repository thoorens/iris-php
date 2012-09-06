<?php

$config1 = new \Iris\SysConfig\Config("Production");
$config1->databaseName = "BaseProd";
$config1->username = 'User';

$config2 = new \Iris\SysConfig\Config("Development");
$config2->databaseName = 'BaseDev';
$config2->setParent($config1, \Iris\SysConfig\_Parser::LINK_TO_PARENT);

$configs = array($config1, $config2);
$fileName = IRIS_ROOT_PATH.'/Test/test.ini';
$builder = \Iris\SysConfig\_Parser::ParserBuilder('ini');
$save = $builder->exportFile($fileName, $configs);
//$config2->Brol = 'Brol';
//unset($config2->Brol);
//unset($config2->databaseName);
//$config1->Brol1 = 'Brol1';
//foreach($config2 as $key=>$value){
//    echo "$key : $value<br/>";
//}
//echo "Propriété héritée: ".$config2->Brol1."<br/>";
//
//$config1->Debug(array($config1,$config2));
die('Fin du test');