<?php

class loadAttributesClass
{

    public function __construct()
    {}

    public function loadAttributes($array)
    {
        $structure = '<ul>';

        for ($i = 0; $i < count($array[0]); $i ++) {

            $structure .= '<li>';
            
            $structure .= '<input type="radio" id="' . $array[0][$i] . '" name="decAttribute"
                    value="' . $array[0][$i] . '">';
            $structure .= '<label for="' . $array[0][$i] . '">' . $array[0][$i] . '</label>';
                $structure .= '<div class="check"></div>';
                
            $structure .= '</li>';
        }

        $structure .= '</ul>';

        return $structure;
    }
}