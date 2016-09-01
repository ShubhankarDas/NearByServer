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
            $r['place_name'] = $row['place_name'];
            $r['tag_name'] = $row['tag_name'];
            $r['url_thumbnail']=$row['url_thumbnail'];
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
            $currentArray["url_thumbnail"]=$duplicateResponse[$i]["url_thumbnail"];
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
if(isset($_GET["search_query"])){
    $search_query = $_GET["search_query"];
    if(!empty($search_query)&&trim($search_query)!=''){
        $query = "select places.place_id,places.url_thumbnail ,places.place_name, place_tags.tag_id, tags.tag_name from place_tags inner join tags on place_tags.tag_id = tags.tag_id left join places on place_tags.place_id = places.place_id where tags.tag_name = '$search_query'";
    }
    else{
    $query = "select places.place_id,places.url_thumbnail ,places.place_name, place_tags.tag_id, tags.tag_name from place_tags inner join tags on place_tags.tag_id =    tags.tag_id left join places on place_tags.place_id = places.place_id";
}
}
else{
    $query = "select places.place_id ,places.place_name,places.url_thumbnail, place_tags.tag_id, tags.tag_name from place_tags inner join tags on place_tags.tag_id = tags.tag_id left join places on place_tags.place_id = places.place_id";
}

$data = get_user_details($dbc,$query);
if(!empty($data)){
    deliver_json(200,$data);
}
else{
    deliver_json(400,$error_invalide_paramters);
}

/*
if($id != $row["place_id"]){
                $id = $row["place_id"];
                $r["place_id"] = $row["place_id"];
                $r["place_name"] = $row["place_name"];
                $r["place_tag"] = $row["tag_name"];
                
                $result[] = $r;
            }
*/

?>

