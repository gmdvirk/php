<?php 

    function new_curl_request($req_type, $url, $data){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url, 
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $req_type,
            CURLOPT_POSTFIELDS =>$data,
            CURLOPT_HTTPHEADER => array(
                'key: GRh1MMgYI07f363XhTuin2MU2jVNoz5E1vKFJsfv',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return [ 
            'response' => $response,
            'status' => $httpcode
        ];
     
    }
?>