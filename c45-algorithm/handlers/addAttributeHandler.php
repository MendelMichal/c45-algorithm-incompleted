<?php
require_once '../classes/addAttributeClass.php';

$decodedArray = json_decode($_POST['data']);
$object = new addAttributeClass();
$result = $object->addAttributes($decodedArray);
$result2 = json_encode($result);

echo $result2;