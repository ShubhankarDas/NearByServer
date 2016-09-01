<?php

require_once('../mysqli_connect.php');
require_once('../includes/error_log.php');
require_once('../deliver_json.php');
header("Content-Type:application/json");

function get_user_details($dbc,$query){
    
    $response = @mysqli_query($dbc, $query);
    $result = array();
    if($response){
        while($row=mysqli_fetch_assoc($response)){
            $r['user_id'] = $row['user_id'];
            $result[] = $r;
        }    
        return $result;
    }
}

if(isset($_GET['user_name'])){
        $user_name = $_GET["user_name"];
    if(!empty($user_name)&&trim($user_name)!=''){
        $query = " SELECT user_id from users where user_name = '$user_name'";
    }
    else{
    $query = "null";
}
}
else{
    $query = "null";
}

$data = get_user_details($dbc,$query);
if(!empty($data)){
    deliver_json(200,$data);
}
else{
    deliver_json(400,$error_invalide_paramters);
}

?>