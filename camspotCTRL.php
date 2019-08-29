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
		echo "<a href=\"/camspotCTRL.php?command=left\"> Left </a>";
		echo "<a href=\"/camspotCTRL.php?command=right\"> Right </a>";
		echo "<a href=\"/camspotCTRL.php?command=up\"> Up </a>";
		echo "<a href=\"/camspotCTRL.php?command=down\"> Down </a>";
		
		$command = $_GET["command"];

		if($command== "left")
			$output = shell_exec("wget -q \"http://192.168.0.101:16319/decoder_control.cgi?user=admin&pwd=."$password".&command=4&onestep=5\" -O /home/pi/motion/camspot.txt");
		if($command== "right")
			$output = shell_exec("wget -q \"http://192.168.0.101:16319/decoder_control.cgi?user=admin&pwd=."$password".&command=6&onestep=5\" -O /home/pi/motion/camspot.txt");
		if($command== "up")
			$output = shell_exec("wget -q \"http://192.168.0.101:16319/decoder_control.cgi?user=admin&pwd=."$password".&command=0&onestep=5\" -O /home/pi/motion/camspot.txt");
		if($command== "down")
			$output = shell_exec("wget -q \"http://192.168.0.101:16319/decoder_control.cgi?user=admin&pwd=."$password".&command=2&onestep=5\" -O /home/pi/motion/camspot.txt");
		
	}
//var PTZ_UP=0;
//var PTZ_UP_STOP=1;
//var PTZ_DOWN=2;
//var PTZ_DOWN_STOP=3;
//var PTZ_LEFT=4;
//var PTZ_LEFT_STOP=5;
//var PTZ_RIGHT=6;
//var PTZ_RIGHT_STOP=7;
//var PTZ_LEFT_UP=90;
//var PTZ_RIGHT_UP=91;
//var PTZ_LEFT_DOWN=92;
//var PTZ_RIGHT_DOWN=93;
//var PTZ_STOP=1;
//var PTZ_CENTER=25;
//上下/水平巡视
//var PTZ_VPATROL=26;
//var PTZ_VPATROL_STOP=27;
//var PTZ_HPATROL=28;
//var PTZ_HPATROL_STOP=29;
//var IO_ON=94;
//var IO_OFF=95;
?>

