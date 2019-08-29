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

	<iframe src=\"http://".$_SERVER['HTTP_HOST']."\\cameractrl.php\" height=\"30\" width=\"600\" ></iframe>
	
	<a href=\"http://".$_SERVER['HTTP_HOST']."\motion.php\"> &#8195;Back </a>
	
	<br>

<img src=http://".$_SERVER['HTTP_HOST'].":8083/ border=0 width=100%>


</body>


</html>";

}}
?>
