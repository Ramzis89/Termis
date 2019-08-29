<?php
$dir    = '/home/pi/motion/images/';
$files1 = scandir($dir);
 include('var.php');
    $username = $USER;
    $password = $PASS;
    
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
} else {
    if(($_SERVER['PHP_AUTH_PW'] == $password) && ($_SERVER['PHP_AUTH_USER'] == $username ))
    {
		
/*$myfile = fopen("/home/pi/motion/temp.txt", "r") or die("Unable to open file!");
$text = fread($myfile,filesize("/home/pi/motion/temp.txt"));
fclose($myfile);

if(strpos($text, "resumed") !== false)
{
	echo "<font color=\"green\"><b>Detection ACTIVE!</b></font><br>
	<form action=\"/mpause.php\" method=\"get\">
<button type=\"submit\" value=\"Submit\">Pause</button>
</form>";
}
else if(strpos($text, "paused") !== false)
{	
	echo "<font color=\"red\"><b>Detection PAUSED!</b></font><br>
	<form action=\"/mstart.php\" method=\"get\">
<button type=\"submit\" value=\"Submit\">Start</button>
</form>";
}*/

echo "<table><tr><td>
		<form action=\"/mstart.php?\" method=\"get\">
		<button type=\"submit\" name=\"cam\" value=\"0\">Start All</button></form>
		</td><td>
		<form action=\"/mpause.php?\" method=\"get\">
		<button type=\"submit\" name=\"cam\" value=\"0\">Pause All</button></form>
		</td></tr></table><br><br>";

echo "<table>";
for($i=0; $i < 4; $i++)
{
	echo "<tr><td>
		<form action=\"/mstart.php?\" method=\"get\">
		<button type=\"submit\" name=\"cam\" value=\"".($i+1)."\" style=\" color: green;\">Start Cam ".($i+1)."</button></form>
		</td><td>
		<form action=\"/mpause.php?\" method=\"get\">
		<button type=\"submit\" name=\"cam\" value=\"".($i+1)."\" style=\" color: red;\">Pause Cam ".($i+1)."</button></form>
		</td><td>
		<form action=\"/detstat.php?\" method=\"get\">
		<button type=\"submit\" name=\"cam\" value=\"".($i+1)."\" style=\" color: blue;\">Status Cam ".($i+1)."</button></form>
		</td><td><a href=\"http://".$_SERVER['HTTP_HOST'].":808".($i+2)."\">View ".($i+1)." Cam</a>
		</td></tr>";
}
echo "</table>
<a href=\"http://".$_SERVER['HTTP_HOST']."\camera.php\">Camera 2 servo</a>
<br><a href=\"http://".$_SERVER['HTTP_HOST']."\camspot.php\">Camera 4 servo</a>";


echo "<table>";
$j=1;
for($i = count($files1); $i > 0; $i--)
{
	if(strlen($files1[$i]) > 2)
	{
		echo "<tr><td><a href=\"open.php?img=".$i."\">".$files1[$i]." ".
		number_format((filesize($dir.$files1[$i])/1024/1024), 2)."   MB</a></td></tr>";
		$j++;
	}
}

echo "</table>
<a href=\"http://".$_SERVER['HTTP_HOST']."\\viewall.php\">View All cameras</a>";
	}
}

?>
