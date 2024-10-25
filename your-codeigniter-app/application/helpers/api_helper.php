<?php

    function response($success, $message, $data = [], $errors =[] ) {
        #$success can be true or http status in fasle case
        $resposneCode = 200;
        if((is_numeric($success) && $success != 200) || $success == false){
            $resposneCode = $success;
            $success = false;
        }else{
            $success = true;
        }
        http_response_code ($resposneCode);
        $resposne = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'errors' => $errors
        ];

        echo json_encode($resposne);
        exit;
    }