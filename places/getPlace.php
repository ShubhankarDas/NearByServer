<?php

require_once('../mysqli_connect.php');
require_once('../includes/error_log.php');
require_once('../deliver_json.php');
header("Content-Type:application/json");

function get_user_details($dbc,$query){
    
    $response = @mysqli_query($dbc, $query);
    $result = array();
    $duplicateResponse = array();
    if($response){
        while($row=mysqli_fetch_assoc($response)){
            $r['place_id'] = $row['place_id'];
            $r['place_area'] = $row['place_area'];
            $r['place_address'] = $row['place_address'];
            $r['phone_no'] = $row['phone_no'];
            $r['likes'] = $row['likes'];
            $r['url_thumbnail'] = $row['url_thumbnail'];
            $r['place_name'] = $row['place_name'];
            $r['tag_name'] = $row['tag_name'];
            $duplicateResponse[] = $r;
        }
        $size = count($duplicateResponse)-1;
        $oldId= $duplicateResponse[0]["place_id"];
        $tags = array();
        $currentArray = array();
        for($i=0;$i<=$size;$i++){
            if($oldId != $duplicateResponse[$i]["place_id"]){
                $oldId = $duplicateResponse[$i]["place_id"];
                $result[] = $currentArray;
                unset($tags);
                unset($$currentArray);
            }
            $currentArray["place_id"]= $duplicateResponse[$i]["place_id"];
            $currentArray["place_name"]= $duplicateResponse[$i]["place_name"];
            $currentArray["place_area"]= $duplicateResponse[$i]["place_area"];
            $currentArray["place_address"]= $duplicateResponse[$i]["place_address"];
            $currentArray["phone_no"]= $duplicateResponse[$i]["phone_no"];
            $currentArray["likes"]= $duplicateResponse[$i]["likes"];
            $currentArray["url_thumbnail"]= $duplicateResponse[$i]["url_thumbnail"];
            $tags[]= $duplicateResponse[$i]["tag_name"];
            $currentArray["place_tag"] = $tags;
            if($i == $size){
                $result[] = $currentArray;
            }
        }
        
    }
    mysqli_close($dbc);    
    return $result;
}
$query="";
if(isset($_GET["place_id"])){
    $search_query = $_GET["place_id"];
    if(!empty($search_query)&&trim($search_query)!=''){
        $query = "select places.place_id,places.place_name,places.place_area,places.place_address,places.likes,places.url_thumbnail,places.phone_no, tags.tag_name from place_tags inner join tags on place_tags.tag_id = tags.tag_id left join places on place_tags.place_id = places.place_id where places.place_id = '$search_query'";
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