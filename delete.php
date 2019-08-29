<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
} else {
    if(($_SERVER['PHP_AUTH_PW'] == "ramzis891") && ($_SERVER['PHP_AUTH_USER'] == "admin"))
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

