<?php

require_once('../mysqli_connect.php');
require_once('../includes/error_log.php');
require_once('../deliver_json.php');
header("Content-Type:application/json");

function get_user_details($dbc,$user_id){
    
    $query = "SELECT user_name , password , mobile_no, created_on FROM users WHERE user_id = ".$user_id;
    $response = @mysqli_query($dbc, $query);
    
    if($response){
        
        $row=mysqli_fetch_array($response);
        
        if(empty($row)){
            mysqli_close($dbc);
            return null;
        }

        $result['user_name'] = $row['user_name'];
        $result['password'] = $row['password'];
        $result['mobile_no'] = $row['mobile_no'];
        $result['created_on'] = $row['created_on'];
        
    }
    mysqli_close($dbc);    
    return $result;
}
if(isset($_GET['id'])){
    $id = $_GET['id'];
}
if(isset($id) && trim($id)<>''){
    $data = get_user_details($dbc,$_GET['id']);
}

if(!empty($data)){
    deliver_json(200,$data);
}
else{
    deliver_json(400,$error_invalide_paramters);
}

?>