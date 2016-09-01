<?php

require_once('../mysqli_connect.php');

if($_SERVER['REQUEST_METHOD']=='POST'){
    $user_id = $_POST['user_id'];
    $place_id = $_POST['place_id'];
    $comment = $_POST['comment'];
  
    $sql = "INSERT INTO reviews (
`review_id` ,
`comment` ,
`comment_entered` ,
`user_id` ,
`place_id`
)
VALUES (
NULL , '$comment', NOW( ) , '$user_id', '$place_id'
);";
    
    if(mysqli_query($dbc,$sql)){    
        echo 'OK';
    }
    else{
        echo 'ERROR';
    }
    
}
else {
    echo 'error';
}
?>