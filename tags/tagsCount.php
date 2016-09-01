<?php

require_once('../mysqli_connect.php');
require_once('../includes/error_log.php');
require_once('../deliver_json.php');
header("Content-Type:application/json");

$sql = "SELECT COUNT(*) as total_tags FROM tags";

$response = @mysqli_query($dbc,$sql);

if($response){
    $row=mysqli_fetch_array($response);
        
        if(empty($row)){
            mysqli_close($dbc);
            return null;
        }

        $result['total_tags'] = $row['total_tags'];
    }
    mysqli_close($dbc);

$data = $result;

if(!empty($data)){
    deliver_json(200,$data);
}
else{
    deliver_json(400,$error_invalide_paramters);
}

?>