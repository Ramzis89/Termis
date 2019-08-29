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


<body>";
for($i = 7 ; $i <= 20; $i++)
	echo "<a href=\"/cameractrl.php?mode=".$i."\"> <".$i."> </a>";
	

echo "</body>


</html>";
$mode = $_GET["mode"];

echo "--[".$mode."]--";

$pin = "1";
		if(($mode > 0) && ($mode < 25))
		{
			system("gpio mode ".$pin." pwm");
			system("gpio pwm-ms");
			system("gpio pwmc 1920");
			system("gpio pwmr 200");
			system("gpio pwm ".$pin." ".$mode);
			system("sleep 1");
			system("gpio mode ".$pin." in");
	}
}}
?>
