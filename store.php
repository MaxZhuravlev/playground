<?php

include_once "/home/maxlord/public_html/qnits/yiiapp.php";

echo "Go <br/>";

$items = ProductOnStore::model()->onDiagnostic()->findAll();
echo ProductOnStore::model()->onDiagnostic()->count()."<br/>";

            $criteria = new CDbCriteria();
            $criteria->compare('modification_id',$modification_id);
            $criteria->limit=1;
            $criteria->offset=$start;
            ProductOnStore::model()->onTheWay->findAll($criteria);

echo "<pre>";

print_r($items);

echo "</pre>";

echo "Finish.";