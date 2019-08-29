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
$mode = $_GET["mode"];
$psw = $_GET["psw"];

$pin = "1";

if($psw == "123")
{
		system("gpio mode ".$pin." pwm");
		system("gpio pwm-ms");
		system("gpio pwmc 1920");
		system("gpio pwmr 200");
		system("gpio pwm ".$pin." ".$mode);
		system("sleep 1");
		system("gpio mode ".$pin." in");

	echo "OK-".$mode;
}
}}
?>
