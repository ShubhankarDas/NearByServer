<?php

require_once('../mysqli_connect.php');
require_once('../includes/error_log.php');
require_once('../deliver_json.php');
header("Content-Type:application/json");

function get_user_details($dbc,$query){
    
    $response = @mysqli_query($dbc, $query);
    $result = array();
    $urls  = array();
    $r = array();
    if($response){
        while($row=mysqli_fetch_assoc($response)){
            $r['place_id'] = $row['place_id'];
            $urls[] = $row['photo_url'];
                
        }
        $r['photo_url'] = $urls;
        $result[] = $r;
        mysqli_close($dbc);    
        return $result;
    }
}
$query="";
if(isset($_GET["place_id"])){
    $search_query = $_GET["place_id"];
    if(!empty($search_query)&&trim($search_query)!=''){
        $query = "select place_id,photo_url from photos where place_id ='$search_query' limit 0,3";
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