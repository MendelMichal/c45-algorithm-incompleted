<?php

include ("generatePreviewClass.php");

class uploadClass
{

    public function __construct()
    {}

    function uploadDataset($formDataset)
    {
        $errors = array();

        $file_name = $formDataset['name'];
        $file_size = $formDataset['size'];
        $file_tmp = $formDataset['tmp_name'];

        $file_ext_explode = explode('.', strtolower($file_name));
        $file_ext = end($file_ext_explode);

        $expensions = array(
            "csv",
            "txt",
            "data"
        );

        if (in_array($file_ext, $expensions) === false) {
            $errors[] = "Niepoprawne rozszerzenie pliku. Dozwolone rozszerzenia to .CSV, .TXT oraz .DATA";
        }

        if ($file_size > 52428800) {
            $errors[] = 'Rozmiar pliku nie może przekraczać 50MB';
        }

        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "../uploaded_datasets/" . $file_name);
            
            $array[0] = [
                'Poprawnie wgrano zbiór danych'
            ];
            $array[1] = $this->arrayMap($file_name);
            
            $object = new generatePreviewClass();
            $array[2] = $object->generatePreview($array[1]);
            return $array;
        } else {
            return $errors[0];
        }
    }
    
    function arrayMap($fileName){
        $generatedArray = array_map('str_getcsv', file('../uploaded_datasets/' . $fileName));
        return $generatedArray;
    }

    function generatePreview($generatedArray)
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