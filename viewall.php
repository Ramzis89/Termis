<?php
 include('var.php');
    $username = $USER;
    $password = $PASS;
    
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
} else {
    if(($_SERVER['PHP_AUTH_PW'] == $password) && ($_SERVER['PHP_AUTH_USER'] == $username))
    {
echo "
<html>
<head>
</head>


<body>

<a href=\"http://".$_SERVER['HTTP_HOST']."\motion.php\"> Back </a>
	
	<br>
<a href=http://".$_SERVER['HTTP_HOST'].":8082> <img src=http://".$_SERVER['HTTP_HOST'].":8082/ border=0 width=48%></a>
<a href=http://".$_SERVER['HTTP_HOST'].":8083> <img src=http://".$_SERVER['HTTP_HOST'].":8083/ border=0 width=48%></a>
<a href=http://".$_SERVER['HTTP_HOST'].":8084> <img src=http://".$_SERVER['HTTP_HOST'].":8084/ border=0 width=48%></a>
<a href=http://".$_SERVER['HTTP_HOST'].":8085> <img src=http://".$_SERVER['HTTP_HOST'].":8085/ border=0 width=48%></a>

</body>


</html>";

}}
?>
