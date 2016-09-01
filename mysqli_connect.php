<?php

define ('DB_USER', 'a3418210_shubh');
define ('DB_PASSWORD', 'shubhina65');
define ('DB_HOST', 'mysql12.000webhost.com');
define ('DB_NAME', 'a3418210_nearby');

$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die('Could not connect to MySQL '.
       mysqli_connect_error());

?>