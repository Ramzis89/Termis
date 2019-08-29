<?php

$security = 0;

if($_GET["psw"] == "apsauga123")
   $security = 1;
   include('var.php');
    $username = $USER;
    $password = $PASS;
   if($security == 0)
   {

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
} else {
    if(($_SERVER['PHP_AUTH_PW'] == $password) && ($_SERVER['PHP_AUTH_USER'] == $username))
    			{
				$security = 1;
			}
		}
	}
	
	if($security == 1)
	{
$cam = $_GET["cam"];

if(strlen($cam) ==1)
{
	if($cam == 0)
	{
		$output = shell_exec("wget -q http://127.0.0.1:8080/1/detection/pause -O /home/pi/motion/temp.txt");
		$output = shell_exec("wget -q http://127.0.0.1:8080/2/detection/pause -O /home/pi/motion/temp.txt");
		$output = shell_exec("wget -q http://127.0.0.1:8080/3/detection/pause -O /home/pi/motion/temp.txt");
		$output = shell_exec("wget -q http://127.0.0.1:8080/4/detection/pause -O /home/pi/motion/temp.txt");
		echo "Detection for all cameras paused!<br><a href=\"motion.php\">Back</a>";
	}
	else
	{
		$output = shell_exec("wget -q http://127.0.0.1:8080/".$cam."/detection/pause -O /home/pi/motion/temp.txt");
		echo "Detection for camera ".$cam." paused!<br><a href=\"motion.php\">Back</a>";
	}
}

}
	
?>
