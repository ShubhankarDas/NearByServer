<?php

require_once('../mysqli_connect.php');
require_once('../includes/error_log.php');
require_once('../deliver_json.php');
header("Content-Type:application/json");

function get_user_details($dbc){
    
    $query = "SELECT tag_id, tag_name FROM tags ";
    $response = @mysqli_query($dbc, $query);
    $result = array();
    if($response){
        
        while($row=mysqli_fetch_assoc($response)){
            $r['tag_code'] = $row['tag_id'];
            $r['tag_name'] = $row['tag_name'];
            $result[] = $r;
        }
        
    }
    mysqli_close($dbc);    
    return $result;
}
$data = get_user_details($dbc);
if(!empty($data)){
    deliver_json(200,$data);
}
else{
    deliver_json(400,$error_invalide_paramters);
}

?>