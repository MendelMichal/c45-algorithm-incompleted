<?php

include ("generatePreviewClass.php");

class addAttributeClass
{

    public function __construct()
    {}

    function addAttributes($decodedArray){
        
        $arrayLength = count($decodedArray[0]);
        $attributes = [];
        
        for($i = 1; $i <= $arrayLength; $i++){
            array_push($attributes, 'x' . $i);
        }
        
        array_unshift($decodedArray, $attributes);
        
        $returnArray[0] = $decodedArray;
        
        $object = new generatePreviewClass();
        
        $returnArray[1] = $object->generatePreview($decodedArray);
        
        return $returnArray;
    }
}