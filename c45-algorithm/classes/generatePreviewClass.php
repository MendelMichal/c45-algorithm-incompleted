<?php

class generatePreviewClass
{

    public function __construct()
    {}

    public function generatePreview($generatedArray)
    {
        $table = '<div class="div-table">';
        $table .= '<table class="dataset-table" cellspacing="0" cellpadding="1">';
        
        for ($i = 0; $i < count($generatedArray); $i ++) {
            if ($i == 0)
                $table .= '<thead>';
                
                $table .= '<tr>';
                for ($j = 0; $j < count($generatedArray[$i]); $j ++) {
                    $table .= '<td>' . $generatedArray[$i][$j] . '</td>';
                }
                $table .= '</tr>';
                
                if ($i == 0)
                    $table .= '</thead>';
        }
        
        $table .= '</table>';
        $table .= '</div>';
        
        return $table;
    }
}