<?php
require_once '../classes/c45AlgorithmClass.php';
require_once '../classes/generateHtmlClass.php';

$decodedArray = json_decode($_POST['data']);
$object = new c45AlgorithmClass();
$objectHtml = new generateHtmlClass();
$attributesEntropy = [];
$attributesGain = [];
$attributesSplitInfo = [];
$attributesGainRatio = [];
$allAttributesCalculations = [];
$calculationsTableHtml = '';

$globalEntropy = $object->calculateGlobalEntropy($decodedArray, $_POST['decision']);

for ($i = 0; $i < count($decodedArray[0]); $i ++) {
    if ($i != $globalEntropy[1]) {
        $attributeEntropy = $object->calculateAttributeEntropy($decodedArray, $i, $globalEntropy[1], $globalEntropy[2]);
        array_push($attributesEntropy, ['index' => $i, 'entropy' => $attributeEntropy, 'type' => $attributeEntropy['type']]);
    }
}

for($i = 0; $i < count($attributesEntropy); $i++){
    $attributeGain = $object->calculateGain($decodedArray, $attributesEntropy[$i], $globalEntropy[0]);
    array_push($attributesGain, $attributeGain);
}

for($i = 0; $i < count($attributesGain); $i++){
    $attributeSplitInfo = $object->calculateSplitInfo($attributesGain[$i]);
    array_push($attributesSplitInfo, $attributeSplitInfo);
}

for($i = 0; $i < count($attributesSplitInfo); $i++){
    $attributeGainRatio = $object->calculateGainRatio($attributesGain[$i], $attributesSplitInfo[$i]);
    array_push($attributesGainRatio, $attributeGainRatio);
}

array_push($allAttributesCalculations, array('attributes' => $decodedArray[0], 'globalEntropy' => $globalEntropy, 'attributesEntropy' => $attributesEntropy,
    'attributesGain' => $attributesGain, 'attributesSplitInfo' => $attributesSplitInfo, 'attributesGainRatio' => $attributesGainRatio));

$calculationsTableHtml = $objectHtml->generateAttributesCalculations($allAttributesCalculations);

$result2 = json_encode(array('globalEntropy' => $globalEntropy, 'tableHtml' => $calculationsTableHtml, 'cos' => $allAttributesCalculations));

echo $result2;