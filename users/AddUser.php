<?php

require_once('../mysqli_connect.php');

if($_SERVER['REQUEST_METHOD']=='POST'){
    $user_name = $_POST['user_name'];
    $full_name = $_POST['full_name'];
    $url = $_POST['user_url'];
    
    $flag_error = 0;
        
    $checkingSql = "SELECT count(user_id) as count FROM a3418210_nearby.users WHERE user_name = '$user_name'";
    
    $res = mysqli_query($dbc,$checkingSql);
    $count = 0;
    
    while($row = mysqli_fetch_array($res)){
        $count=$row['count'];
    }
    
    if($count == 0){
        $sql = "INSERT INTO a3418210_nearby.users (user_id ,user_name ,password ,full_name ,mobile_no ,profile_pic_url ,created_on) VALUES (NULL , '$user_name', 'pass', '$full_name', NULL ,'$url' , NOW( ));";
            
        if(mysqli_query($dbc,$sql)){    
        }
    }
    
    $id="";
    $sql2= "SELECT user_id from users where user_name = '$user_name'";
    $response = @mysqli_query($dbc,$sql2);
    while($row=mysqli_fetch_assoc($response)){
        $id = $row['user_id'];    
    }
        echo $id;
    mysqli_close($dbc);
}
?>