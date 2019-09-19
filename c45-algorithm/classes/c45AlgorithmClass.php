<?php

class c45AlgorithmClass
{

    public function __construct()
    {}

    function calculateGlobalEntropy($datasetArray, $decisionName)
    {
        $amountCases = count($datasetArray);
        $decisionIndex = array_search($decisionName, $datasetArray[0]);
        $decisionColumnArray = [];
        $decisionList = [];
        $decisionAmount = [];
        $math = '';

        for ($i = 1; $i < $amountCases; $i ++)
            array_push($decisionColumnArray, $datasetArray[$i][$decisionIndex]);

        $decisionUniqueList = array_unique($decisionColumnArray);
        foreach ($decisionUniqueList as $decision)
            array_push($decisionList, $decision);

        $amountDecisions = count($decisionList);

        for ($i = 0; $i < $amountDecisions; $i ++) {
            $amount = 0;
            for ($j = 1; $j < $amountCases; $j ++) {
                if ($datasetArray[$j][$decisionIndex] == $decisionList[$i]) {
                    $amount ++;
                }
            }
            $decisionAmount[$i] = $amount;
        }

        for ($i = 0; $i < $amountDecisions; $i ++) {
            $math .= '-(' . $decisionAmount[$i] . '/' . ($amountCases - 1) . ') * log(' . $decisionAmount[$i] . '/' . ($amountCases - 1) . ', 2)';
        }

        $result = [
            round(eval('return ' . $math . ';'),3),
            $decisionIndex,
            $decisionList
        ];
        return $result;
    }

    function checkValuesType($datasetArray, $index)
    {
        if (is_numeric($datasetArray[1][$index])) {
            $type = 'numeric';
        } else {
            $type = 'string';
        }

        for ($i = 2; $i < count($datasetArray); $i ++) {
            if ($type == 'numeric') {
                if (! is_numeric($datasetArray[$i][$index])) {
                    return 'Błąd integracji danych';
                }
            }
            if ($type == 'string') {
                if (is_numeric($datasetArray[$i][$index])) {
                    return 'Błąd integracji danych';
                }
            }
        }
        return $type;
    }

    function calculateAttributeEntropy($datasetArray, $index, $decisionIndex, $decisionList)
    {
        $attributeColumn = [];
        $tempDatasetArray = [];
        $uniqueValuesList = [];
        $valuesDecisionAmountLessOrEqual = [];
        $valuesDecisionAmount = [];
        $valuesDecisionAmountMore = [];
        $calculatedEntropy = [];
        $totalNumberOfDecisionCases = [];

        $type = $this->checkValuesType($datasetArray, $index);
        if ($type == 'numeric') {

            for ($i = 1; $i < count($datasetArray); $i ++)
                array_push($attributeColumn, $datasetArray[$i][$index]); //zapełnianie tablicy kolumną atrybutu przekazanego w pętli w pliku algorithHandler.php

            $uniqueValues = array_unique($attributeColumn); //wartosci unikatowe z kolumny atrybutu
            foreach ($uniqueValues as $value)
                array_push($uniqueValuesList, floatval($value)); //przeksztalcenie na float owych wartosci
            
            sort($uniqueValuesList); //Sortowanie od najnizszej do najwyzszej

            for ($i = 1; $i < count($datasetArray); $i ++) {
                $tempDatasetArray[$i - 1][0] = floatval($datasetArray[$i][$index]); //wartosci atrybutu oraz typ float
                $tempDatasetArray[$i - 1][1] = $datasetArray[$i][$decisionIndex]; //kolumna decyzyjna
            }

            for ($i = 0; $i < count($uniqueValuesList); $i ++) {
                for ($k = 0; $k < count($decisionList); $k ++) {
                    $lessOrEqualAmount = 0;
                    $moreAmount = 0;
                    for ($j = 0; $j < count($tempDatasetArray); $j ++) {
                        if ($tempDatasetArray[$j][1] == $decisionList[$k]) {
                            if ($tempDatasetArray[$j][0] <= $uniqueValuesList[$i])
                                $lessOrEqualAmount ++;
                            else
                                $moreAmount ++;
                        }
                    }
                    $valuesDecisionAmountLessOrEqual[$i][$k] = $lessOrEqualAmount;
                    $valuesDecisionAmountMore[$i][$k] = $moreAmount;
                }
            }

            for ($i = 0; $i < count($uniqueValuesList); $i ++) {
                $totalNumberOfDecisionCases1 = 0;
                $totalNumberOfDecisionCases2 = 0;
                for ($j = 0; $j < count($decisionList); $j ++) {
                    $totalNumberOfDecisionCases1 = $totalNumberOfDecisionCases1 + $valuesDecisionAmountLessOrEqual[$i][$j];
                    $totalNumberOfDecisionCases2 = $totalNumberOfDecisionCases2 + $valuesDecisionAmountMore[$i][$j];
                }
                $totalNumberOfDecisionCases[$i] = [
                    $totalNumberOfDecisionCases1,
                    $totalNumberOfDecisionCases2
                ];
            }

            for ($i = 0; $i < count($uniqueValuesList); $i ++) {
                $entropyMathLessOrEqual = '';
                $entropyMathMore = '';

                for ($k = 0; $k < count($decisionList); $k ++) {
                    if ($totalNumberOfDecisionCases[$i][0] != 0) {
                        if ($valuesDecisionAmountLessOrEqual[$i][$k] == 0) {
                            $entropyMathLessOrEqual .= '-0';
                        } else {
                            $entropyMathLessOrEqual .= '-(' . $valuesDecisionAmountLessOrEqual[$i][$k] . '/' . $totalNumberOfDecisionCases[$i][0] . ') * log(' . $valuesDecisionAmountLessOrEqual[$i][$k] . '/' . $totalNumberOfDecisionCases[$i][0] . ', 2)';
                        }
                    } else {
                        $entropyMathLessOrEqual = '0';
                        break;
                    }

                    if ($totalNumberOfDecisionCases[$i][1] != 0) {
                        if ($valuesDecisionAmountMore[$i][$k] == 0) {
                            $entropyMathMore .= '-0';
                        } else {
                            $entropyMathMore .= '-(' . $valuesDecisionAmountMore[$i][$k] . '/' . $totalNumberOfDecisionCases[$i][1] . ') * log(' . $valuesDecisionAmountMore[$i][$k] . '/' . $totalNumberOfDecisionCases[$i][1] . ', 2)';
                        }
                    } else {
                        $entropyMathMore = '0';
                        break;
                    }
                }

                if ($entropyMathLessOrEqual != '0') {
                    $calculatedEntropy[$i][0] = round(abs(eval('return ' . $entropyMathLessOrEqual . ';')),3);
                } else {
                    $calculatedEntropy[$i][0] = 0;
                }
                if ($entropyMathMore != '0') {
                    $calculatedEntropy[$i][1] = round(abs(eval('return ' . $entropyMathMore . ';')),3);
                } else {
                    $calculatedEntropy[$i][1] = 0;
                }
            }
        } else {
            for ($i = 1; $i < count($datasetArray); $i ++)
                array_push($attributeColumn, $datasetArray[$i][$index]);
                
            $uniqueValues = array_unique($attributeColumn);
            foreach ($uniqueValues as $value)
                array_push($uniqueValuesList, $value);
                    
            for ($i = 1; $i < count($datasetArray); $i ++) {
                 $tempDatasetArray[$i - 1][0] = $datasetArray[$i][$index]; //Akualny liczony atrybut
                 $tempDatasetArray[$i - 1][1] = $datasetArray[$i][$decisionIndex]; //Kolumna decyzyjna
            }
            
            for ($i = 0; $i < count($uniqueValuesList); $i ++) {
                for ($k = 0; $k < count($decisionList); $k ++) {
                    $amount = 0;
                    for ($j = 0; $j < count($tempDatasetArray); $j ++) {
                        if($tempDatasetArray[$j][1] == $decisionList[$k] && $tempDatasetArray[$j][0] == $uniqueValuesList[$i]){
                            $amount = $amount + 1;
                        }
                    }
                    $valuesDecisionAmount[$i][$k] = $amount;
                }
            }
            
            for ($i = 0; $i < count($uniqueValuesList); $i ++) {
                $totalNumberOfDecisionCasesGeneral = 0;
                for ($j = 0; $j < count($decisionList); $j ++) {
                    $totalNumberOfDecisionCasesGeneral = $totalNumberOfDecisionCasesGeneral + $valuesDecisionAmount[$i][$j];
                }
                $totalNumberOfDecisionCases[$i] = [
                    $totalNumberOfDecisionCasesGeneral,
                ];
            }
            
            for ($i = 0; $i < count($uniqueValuesList); $i ++) {
                $entropyMath = '';
                
                for ($k = 0; $k < count($decisionList); $k ++) {
                    if ($totalNumberOfDecisionCases[$i][0] != 0) {
                        if ($valuesDecisionAmount[$i][$k] == 0) {
                            $entropyMath .= '-0';
                        } else {
                            $entropyMath .= '-(' . $valuesDecisionAmount[$i][$k] . '/' . $totalNumberOfDecisionCases[$i][0] . ') * log(' . $valuesDecisionAmount[$i][$k] . '/' . $totalNumberOfDecisionCases[$i][0] . ', 2)';
                        }
                    } else {
                        $entropyMath = '0';
                        break;
                    }
                }
                
                if ($entropyMath != '0') {
                    $calculatedEntropy[$i][0] = round(abs(eval('return ' . $entropyMath . ';')),3);
                } else {
                    $calculatedEntropy[$i][0] = 0;
                }
            }

        }

        $result = [
            'uniqueValues' => $uniqueValuesList,
            'entropy' => $calculatedEntropy,
            'type' => $type,
        ];

        return $result;
    }

    function calculateGain($datasetArray, $entropy, $globalEntropy)
    {
        $amountCases = count($datasetArray) - 1;
        $lessOrEqual = [];
        $more = [];
        $amount = [];
        $gainResults = [];

        if($entropy['type'] == 'numeric'){
            for ($i = 0; $i < count($entropy['entropy']['uniqueValues']); $i ++) {
                $lessOrEqualAmount = 0;
                $moreAmount = 0;
                for ($j = 1; $j < count($datasetArray); $j ++) {
                    if ($entropy['entropy']['uniqueValues'][$i] >= $datasetArray[$j][$entropy['index']]) {
                        $lessOrEqualAmount ++;
                    } else {
                        $moreAmount ++;
                    }
                }
                $lessOrEqual[$i] = $lessOrEqualAmount;
                $more[$i] = $moreAmount;
            }
    
            for ($i = 0; $i < count($entropy['entropy']['uniqueValues']); $i ++) {
                $gain = $globalEntropy . ' - (' . $lessOrEqual[$i] . '/' . $amountCases . ') * ' . $entropy['entropy']['entropy'][$i][0] . ' - (' . $more[$i] . '/' . $amountCases . ') * ' . $entropy['entropy']['entropy'][$i][1];
                $gainResults[$i] = round(eval('return ' . $gain . ';'),3);
            }
    
            return [
                $gainResults,
                $amountCases,
                $lessOrEqual,
                $more
            ];
        } else {
            for ($i = 0; $i < count($entropy['entropy']['uniqueValues']); $i ++) {
                $generalAmount = 0;
                for ($j = 1; $j < count($datasetArray); $j ++) {
                    if ($entropy['entropy']['uniqueValues'][$i] == $datasetArray[$j][$entropy['index']]) {
                        $generalAmount++;
                    }
                }
                $amount[$i] = $generalAmount;
            }
            
            $gain = (string)$globalEntropy;
            for ($i = 0; $i < count($entropy['entropy']['uniqueValues']); $i ++) {
                $gain .= ' - (' . $amount[$i] . '/' . $amountCases . ') * ' . $entropy['entropy']['entropy'][$i][0];
            }
            $gainResults = round(eval('return ' . $gain . ';'),3);
            
            return [
                $gainResults,
                $amountCases,
                $amount,
            ];
        }
    }

    function calculateSplitInfo($attributesGain)
    {
        $amountCases = $attributesGain[1];
        if(is_array($attributesGain[0])){
            $splitInfoArray = [];
            for ($i = 0; $i < count($attributesGain[0]); $i ++) {
                $splitInfo = '';
                if ($attributesGain[2][$i] != 0)
                    $splitInfo .= '-(' . $attributesGain[2][$i] . '/' . $amountCases . ') * log(' . $attributesGain[2][$i] . '/' . $amountCases . ', 2)';
                else
                    $splitInfo .= '-0';
    
                if ($attributesGain[3][$i] != 0)
                    $splitInfo .= ' -(' . $attributesGain[3][$i] . '/' . $amountCases . ') * log(' . $attributesGain[3][$i] . '/' . $amountCases . ', 2)';
                else
                    $splitInfo .= '-0';
    
                $splitInfoArray[$i] = round(abs(eval('return ' . $splitInfo . ';')),3);
            }
            return $splitInfoArray;
        } else {
            $splitInfo = '';
            for ($i = 0; $i < count($attributesGain[2]); $i ++) {
                if ($attributesGain[2][$i] != 0)
                    $splitInfo .= '-(' . $attributesGain[2][$i] . '/' . $amountCases . ') * log(' . $attributesGain[2][$i] . '/' . $amountCases . ', 2)';
                else
                    $splitInfo .= '-0';
         
            }
            $splitInfo = round(abs(eval('return ' . $splitInfo . ';')),3);
            return $splitInfo;
        }
    }

    function calculateGainRatio($gain, $splitInfo)
    {
        if(is_array($gain[0])){
            $gainRatioArray = [];
            for ($i = 0; $i < count($gain[0]); $i++) {
                if ($splitInfo[$i] == 0) {
                    $gainRatioArray[$i] = 0;
                } else {
                    $gainRatioArray[$i] = round(($gain[0][$i] / $splitInfo[$i]),3);
                }
            }
    
            return $gainRatioArray;
        } else {
            $gainRatio = 0;
            if ($splitInfo == 0) {
                $gainRatio = 0;
            } else {
                $gainRatio = round(($gain[0] / $splitInfo),3);
            }
            
            return $gainRatio;
        }
    }
}