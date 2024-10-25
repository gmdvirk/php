<?php

    function pretty_json($jsonString){
        if($jsonString != '' || !is_null($jsonString)){
            json_decode($jsonString);
 		    $isValidJosn =  (json_last_error() == JSON_ERROR_NONE);
            if(!$isValidJosn){
                return '';
            }else{
               return json_encode(json_decode($jsonString), JSON_PRETTY_PRINT);
            }
        }else{
            return '';
        }
    }

    function is_json_valid($jsonString){
        json_decode($jsonString);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    function minify_json($jsonString){
        if($jsonString != '' && !is_null($jsonString)){
            return json_encode(json_decode($jsonString));
        }else{
            return '';
        }
    }
?>