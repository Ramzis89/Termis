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
$dir    = '/home/pi/motion/images/';

$image = $_GET["img"];
if(strlen($image) > 0)
{
	echo "<br><a href=\"del.php?img=".$image."\">Delete this record</a><br>";
	echo "<br><a href=\"motion.php\">Back</a>";
}
}}
?>

