<?php


include "/home/maxlord/public_html/qnits/yiiapp.php";

$modification = ProductModification::model()->findByPk(1);

//print_r($modification->sources);

$source=$modification->sources->findByAttributes(array("provider_id"=>7));

print_r($source);