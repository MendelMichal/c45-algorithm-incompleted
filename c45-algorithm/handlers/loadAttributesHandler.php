<?php
require_once '../classes/loadAttributesClass.php';

$decodedArray = json_decode($_POST['data']);
$object = new loadAttributesClass();
$result = $object->loadAttributes($decodedArray);

echo $result;