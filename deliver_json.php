<?php

function deliver_json($status,$data){
    $jsonData = new stdClass();
    $jsonData->status = $status;
    $jsonData->result = $data;
    $response1['status'] = $status;
    $response1['result'] = $data;
    $response['results'] = $response1;
    #echo json_encode(array_values($response));
    echo json_encode($jsonData);
}

?>