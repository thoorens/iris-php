<?php
return;
//
//iris_debug(\models\TSequence::GetItem('/main/index/index'));

$test = \Iris\DB\DataBrowser\AutoEntity::EntityBuilder('sequencetest')->getMetadata();
iris_debug($test->serialize(), \FALSE);
$test->unserialize('PRIMARY@id!FR');
$test->unserialize('FOREIGN@0+section_id!Label+sections+id!id2');
iris_debug($test, \FALSE);
die('ok');