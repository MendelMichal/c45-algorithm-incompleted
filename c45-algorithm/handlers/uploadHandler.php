<?php
require_once '../classes/uploadClass.php';

if (isset($_FILES['file'])) {
    $formDataset = $_FILES['file'];
    $object = new uploadClass();
    $result = $object->uploadDataset($formDataset);
    $result2 = json_encode($result);
    
    echo $result2;
} else {
    $noFile = json_encode(['Brak załączonego pliku']);
    echo $noFile;
}