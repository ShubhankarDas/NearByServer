<?php

require_once('../mysqli_connect.php');
require_once('../includes/error_log.php');
require_once('../deliver_json.php');
header("Content-Type:application/json");


$query="";
if(isset($_GET["lat"])&&isset($_GET["long"])&&isset($_GET["rad"])){
    $lat = $_GET["lat"];
    $long = $_GET["long"];
    $rad = $_GET["rad"];
    if(!empty($lat)&&trim($lat)!=''&&!empty($long)&&trim($long)!=''&&!empty($rad)&&trim($rad)!=''){
        
        $R = 6371;  // earth's mean radius, km
        
        // first-cut bounding box (in degrees)
    $maxLat = $lat + rad2deg($rad/$R);
    $minLat = $lat - rad2deg($rad/$R);
    // compensate for degrees longitude getting smaller with increasing latitude
    $maxLon = $long + rad2deg($rad/$R/cos(deg2rad($lat)));
    $minLon = $long - rad2deg($rad/$R/cos(deg2rad($lat)));
        
        if(isset($_GET["search_query"])){
            $search_query = $_GET["search_query"];
            if(!empty($search_query)&&trim($search_query)!=''){
                $query = "select places.latitude,places.place_area,places.place_address, places.phone_no, places.likes, places.longitude,places.place_id,places.url_thumbnail ,places.place_name, place_tags.tag_id, tags.tag_name from place_tags inner join tags on place_tags.tag_id = tags.tag_id left join places on place_tags.place_id = places.place_id where places.latitude between '$minLat' and '$maxLat' and places.longitude between '$minLon' and '$maxLon' having tags.tag_name = '$search_query' limit 0,80";
            }else{
                $query = "select places.latitude,places.place_area,places.place_address, places.phone_no, places.likes, places.longitude,places.place_id,places.url_thumbnail ,places.place_name, place_tags.tag_id, tags.tag_name from place_tags inner join tags on place_tags.tag_id = tags.tag_id left join places on place_tags.place_id = places.place_id where places.latitude between '$minLat' and '$maxLat' and places.longitude between '$minLon' and '$maxLon' limit 0,80";
            }
        
        }else{
        
        
        $query = "select places.latitude,places.place_area,places.place_address, places.phone_no, places.likes, places.longitude,places.place_id,places.url_thumbnail ,places.place_name, place_tags.tag_id, tags.tag_name from place_tags inner join tags on place_tags.tag_id = tags.tag_id left join places on place_tags.place_id = places.place_id where places.latitude between '$minLat' and '$maxLat' and places.longitude between '$minLon' and '$maxLon' limit 0,80";
        }
    
        $data = get_user_details($dbc,$query);
        if(!empty($data)){
            deliver_json(200,$data);
        }
        else{
            deliver_json(400,$error_invalide_paramters);
        }
    }
    
    else{
        deliver_json(400,$error_invalide_paramters);
    }
}
else{
    deliver_json(400,$error_invalide_paramters);
}

function get_user_details($dbc,$query){
    
    $response = @mysqli_query($dbc, $query);
    $result = array();
    $duplicateResponse = array();
    if($response){
        while($row=mysqli_fetch_assoc($response)){
            $r['place_id'] = $row['place_id'];
            $r['place_name'] = $row['place_name'];
            $r['place_area'] = $row['place_area'];
            $r['place_address'] = $row['place_address'];
            $r['phone_no'] = $row['phone_no'];
            $r['likes'] = $row['likes'];
            $r['tag_name'] = $row['tag_name'];
            $r['latitude'] = $row['latitude'];
            $r['longitude'] = $row['longitude'];
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
            $currentArray["place_area"]= $duplicateResponse[$i]["place_area"];
            $currentArray["place_address"]= $duplicateResponse[$i]["place_address"];
            $currentArray["phone_no"]= $duplicateResponse[$i]["phone_no"];
            $currentArray["likes"]= $duplicateResponse[$i]["likes"];
            $currentArray["url_thumbnail"]=$duplicateResponse[$i]["url_thumbnail"];
            $re["latitude"]=$duplicateResponse[$i]["latitude"];
            $re["longitude"]=$duplicateResponse[$i]["longitude"];
            $currentArray["geo_location"]= $re;
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


?>

