<?php

class generateHtmlClass
{

    public function __construct()
    {}

    function generateAttributesCalculations($calculations){
        unset($calculations[0]['attributes'][$calculations[0]['globalEntropy'][1]]);
        $calculations[0]['attributes'] = array_values($calculations[0]['attributes']);
        
        $table = '';
        
        for($i = 0; $i < count($calculations[0]['attributes']); $i++){
            $table .= '<h4 class="margin-top-60">'.($i+1).'. '.$calculations[0]['attributes'][$i].'</h4><hr>';
            $table .= '<b><h5 class="margin-bottom-20">Entropy</h5></b>';
            $table .= '<table class="margin-bottom-20 blueTable">';
            
                $table .= '<tr>';
                    $table .= '<th>Nazwa</th>';
                    $table .= '<th>Entropy</th>';
                $table .= '</tr>';
                
                if($calculations[0]['attributesEntropy'][$i]['type'] == 'string'){
                    for($j = 0; $j < count($calculations[0]['attributesEntropy'][$i]['entropy']['uniqueValues']); $j++){
                            $table .= '<tr>';
                                $table .= '<td>'.$calculations[0]['attributesEntropy'][$i]['entropy']['uniqueValues'][$j].'</td>';
                                $table .= '<td>'.$calculations[0]['attributesEntropy'][$i]['entropy']['entropy'][$j][0].'</td>';
                            $table .= '</tr>';
                    }
                
                $table .= '</table>';
                
                $table .= '<b><h5 class="margin-bottom-20">Gain</h5></b>';
                $table .= '<div class="margin-bottom-20"> Współczynnik Gain dla atrybutu wyniósł - <b>'.$calculations[0]['attributesGain'][$i][0].'</b></div>';
                
                $table .= '<b><h5 class="margin-bottom-20">SplitInfo</h5></b>';
                $table .= '<div class="margin-bottom-20"> Współczynnik SplitInfo dla atrybutu wyniósł - <b>'.$calculations[0]['attributesSplitInfo'][$i].'</b></div>';
                
                $table .= '<b><h5 class="margin-bottom-20">GainRatio</h5></b>';
                $table .= '<div class="margin-bottom-60"> Współczynnik GainRatio dla atrybutu wyniósł - <b>'.$calculations[0]['attributesGainRatio'][$i].'</b></div>';
                } else {
                    for($j = 0; $j < count($calculations[0]['attributesEntropy'][$i]['entropy']['uniqueValues']); $j++){
                        $table .= '<tr>';
                            $table .= '<td>'.$calculations[0]['attributes'][$i].' <= '.$calculations[0]['attributesEntropy'][$i]['entropy']['uniqueValues'][$j].'</td>';
                            $table .= '<td>'.$calculations[0]['attributesEntropy'][$i]['entropy']['entropy'][$j][0].'</td>';
                        $table .= '</tr>';
                        
                        $table .= '<tr>';
                            $table .= '<td>'.$calculations[0]['attributes'][$i].' > '.$calculations[0]['attributesEntropy'][$i]['entropy']['uniqueValues'][$j].'</td>';
                            $table .= '<td>'.$calculations[0]['attributesEntropy'][$i]['entropy']['entropy'][$j][1].'</td>';
                        $table .= '</tr>';
                    }
                    
                    $table .= '</table>';
                    
                    $table .= '<b><h5 class="margin-top-20 margin-bottom-20">Gain</h5></b>';
                    for($k = 0; $k < count($calculations[0]['attributesEntropy'][$i]['entropy']['uniqueValues']); $k++){
                        $table .= '<div> Współczynnik Gain dla '.$calculations[0]['attributes'][$i].' <> '.$calculations[0]['attributesEntropy'][$i]['entropy']['uniqueValues'][$k].' wyniósł - <b>'.$calculations[0]['attributesGain'][$i][0][$k].'</b></div>';
                    }
                    
                    $table .= '<b><h5 class="margin-top-20 margin-bottom-20">SplitInfo</h5></b>';
                    for($k = 0; $k < count($calculations[0]['attributesEntropy'][$i]['entropy']['uniqueValues']); $k++){
                        $table .= '<div> Współczynnik SplitInfo dla '.$calculations[0]['attributes'][$i].' <> '.$calculations[0]['attributesEntropy'][$i]['entropy']['uniqueValues'][$k].' wyniósł - <b>'.$calculations[0]['attributesSplitInfo'][$i][$k].'</b></div>';
                    }
                    
                    $table .= '<b><h5 class="margin-top-20 margin-bottom-20">GainRatio</h5></b>';
                    for($k = 0; $k < count($calculations[0]['attributesEntropy'][$i]['entropy']['uniqueValues']); $k++){
                        $table .= '<div> Współczynnik GainRatio dla '.$calculations[0]['attributes'][$i].' <> '.$calculations[0]['attributesEntropy'][$i]['entropy']['uniqueValues'][$k].' wyniósł - <b>'.$calculations[0]['attributesGainRatio'][$i][$k].'</b></div>';
                    }
                }
        }
        
        return $table;
    }
}