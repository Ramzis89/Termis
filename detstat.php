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
$cam = $_GET["cam"];

$output = shell_exec("wget -q http://127.0.0.1:8080/".$cam."/detection/status -O /home/pi/motion/temp.txt");
		
$myfile = fopen("/home/pi/motion/temp.txt", "r") or die("Unable to open file!");
$text = fread($myfile,filesize("/home/pi/motion/temp.txt"));
fclose($myfile);


if(strpos($text, "ACTIVE") !== false)
{
	echo "<font color=\"green\"><b>Detection ACTIVE!</b></font><br>";
	//<form action=\"/mpause.php\" method=\"get\">
//<button type=\"submit\" value=\"Submit\">Pause</button>
//</form>";
}
else if(strpos($text, "PAUSE") !== false)
{	
	echo "<font color=\"red\"><b>Detection PAUSED!</b></font><br>";
//	<form action=\"/mstart.php\" method=\"get\">
//<button type=\"submit\" value=\"Submit\">Start</button>
//</form>";
}
}}
?>
