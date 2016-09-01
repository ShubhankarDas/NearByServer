<?php

require_once('../mysqli_connect.php');

if($_SERVER['REQUEST_METHOD']=='POST'){
    $place_name = $_POST['place_name'];
    $place_area = $_POST['place_area'];
    $place_address = $_POST['place_address'];
    $url_thumbnail = $_POST['url_thumbnail'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $phone_no = $_POST['phone_no'];
    $website = $_POST['website'];
    $tags_string = $_POST['tags_string'];
    
    if(strcmp($phone_no,"0") == 0){
        $phone_no = "NULL";
    }
    if(strcmp($website,"null") == 0){
        $website = "NULL";
    }
    $tag_array = explode(",", $tags_string);
    
    $name = $place_name.substr($latitude,-4);
    $file_name = preg_replace('/\s+/', '', $name);
    
    $path = "../../images/$file_name.png";
    
    $actualpath = "http://nbplaces.in/images/$file_name.png";
    
    $sql = "INSERT INTO a3418210_nearby.places (place_id, place_name,place_area, place_address, likes, url_thumbnail, latitude, longitude,phone_no,website,status) VALUES (NULL, '$place_name','$place_area', '$place_address', 0, '$actualpath', '$latitude','$longitude','$phone_no','$website','inactive');";
    
    
    if(mysqli_query($dbc,$sql)){
        file_put_contents($path, base64_decode($url_thumbnail));
        if(mysqli_multi_query($dbc,get_multiple_sql($dbc,$tag_array))){
            echo 'Both';    
        }
        else{
            echo 'Could Not Register';
        }
        echo 'Successfully Registered';
    }else{
        echo 'Could Not Register';
    }
mysqli_close($dbc);
}
else{
    echo 'Invalid Requesthoho';
}

function get_multiple_sql($dbc,$tags){
    
    $getIdQuery="select max(place_id) as id from places;";
    $id="";
    $response = @mysqli_query($dbc, $getIdQuery);
    
    if($response){
        while($row=mysqli_fetch_assoc($response)){
            $id = $row["id"];
        }
    }
    
    $multisql = "";
    foreach((array)$tags as $t){
        $multisql .= "INSERT INTO a3418210_nearby.place_tags (tag_id, place_id) VALUES ('$t','$id');";    
    }
    return $multisql;
}

?>