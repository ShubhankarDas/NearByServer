<?php

require_once('../mysqli_connect.php');
require_once('../includes/error_log.php');
require_once('../deliver_json.php');
header("Content-Type:application/json");

function get_user_details($dbc,$query){
    
    $response = @mysqli_query($dbc, $query);
    $result = array();
    $comments  = array();
    $r = array();
    if($response){
        while($row=mysqli_fetch_assoc($response)){
            $r['place_id'] = $row['place_id'];
            $comment['user_id'] = $row['user_id'];
            $comment['comment'] = $row['comment'];
            $comment['user_url'] = $row['profile_pic_url'];
            $comment['full_name'] = $row['full_name'];
            $comment['date'] = $row['date'];
            $comments[] = $comment;
                
        }
        $r['comments'] = $comments;
        $result[] = $r;
        mysqli_close($dbc);    
        return $result;
    }
}
$query="";
if(isset($_GET["place_id"])){
    $search_query = $_GET["place_id"];
    if(!empty($search_query)&&trim($search_query)!=''){
        $query = " SELECT r.place_id, r.user_id, r.comment, r.comment_entered AS date, u.full_name, u.profile_pic_url
FROM reviews r, users u
WHERE r.user_id = u.user_id
AND r.place_id ='$search_query' order by date desc limit 0,3";
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