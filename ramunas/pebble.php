<?php

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$fp = @fopen("./namuoseGrafikasLaikas.txt", 'r');
$laikas = fgets($fp);
fclose($fp);

$date = new DateTime("now");
$date->setTimezone(new DateTimeZone('Europe/Vilnius'));
$season_time = $date->format('P')." hour";
$season_time = substr($season_time, 2, 1);

$hours = $season_time;

//$time_start = microtime_float();
//$season_time/100

if($laikas < 90)
   $day = $laikas;
else
   $day = 1.00;
  
  $days = floor($day);
  $hours = ($day - $days)*100;
  $hours = $season_time - $hours;
  $str = "+".$hours." hour, -".$days." day";
  $str = "-1 day";
$sekundes = strtotime($str);

//echo "<".$str.">  ";

if($days <= 0)
  $days = 1;


$fp = @fopen("./namuoseGrafikas.txt", 'r');
$grafikas = fgets($fp);
fclose($fp);

if(strpos($grafikas, "SLEGIS") !== false)
  $slegis = 1;
else
  $slegis = 0;

$fp = @fopen($grafikas, 'r');
fseek($fp, -30000*$days, SEEK_END);
$i = 0;
 while (($linex = fgets($fp)) !== false) {
 $line = explode("|", $linex);
  if($line[1] > $sekundes)
  {
    $datay[$i] = $line[0];
    $i++;

  }
}

//Triuksmo salinimas
for($cnt = 2; $cnt < count($datay)-3; $cnt++)
  {
    if(abs($datay[$cnt]-$datay[$cnt-1]) > 0.6)//$ydata0[$cnt-1]*0.07)//Randamas triuksmo
    {
    //echo PHP_EOL.$cnt." ";
      if(abs($datay[$cnt+1]-$datay[$cnt-1]) > 0.6)//$ydata1[$cnt-1]*0.07)//Jei du taskai triuksme
      {
        if(abs($datay[$cnt+2]-$datay[$cnt-1]) > 0.6)//$ydata1[$cnt-1]*0.07)//Jei trecias->tai taip turi but
        {
          
        }
        else//Taisom du taskus
        {
          $datay[$cnt] = $datay[$cnt-1];
          $datay[$cnt+1] = $datay[$cnt-1];
        }
      }
      else//Taisom viena taska
      {
        $datay[$cnt] = $datay[$cnt-1];
      }
     }

  }
  
fclose($fp);
$array_size = $i;
$ampFlag = 0;
$vid_sk = floor(($i-1)/144);
$j = 0;

$min = $datay[$array_size-1];
$max = $datay[$array_size-1];

for($cnt = 144; $cnt > 0; $cnt--)
{
  $vid = $datay[$array_size-$cnt*$vid_sk];
  for($cntv = 0; $cntv < $vid_sk; $cntv++)
  {
  if($datay[$array_size-$cnt*$vid_sk-$cntv] > $vid)
    $vid = $datay[$array_size-$cnt*$vid_sk-$cntv];
  }
  $array[$j] = $vid;
  $j++;
  if($vid > 100)
     $ampFlag = 1;

 if($vid > $max)
	  $max = $vid;
  
  if($vid < $min)
	  $min = $vid;
}

if($min < 0)
{
	$corr = $min * -1;
	for($cnt = 0; $cnt < 144; $cnt++)
		$array[$cnt] = $array[$cnt] + $corr;
}

for($k=0; $k < $j; $k++)
{
  $tValue = intval($array[$k]);
  if($ampFlag == 1)
  { 
    if($slegis == 0)
      $tValue/=1000;
    else
      $tValue/=10;
    $tValue = intval($tValue);
  }
   
   
  $text = $text.$tValue." ";
  
  
  if($ampFlag == 1)
  {
    if($slegis == 0)
      $val = intval(($array[$k]-$tValue*1000)/10);
    else
      $val = intval(($array[$k]-$tValue*10)*10);
  }
  else
  {
    $val = intval(($array[$k]-$tValue)*100);
  }
     
     //echo "<".$array[$k]."; ";
     //echo $tValue."; ";
     //echo intval($val).">";
  $text = $text.intval($val)." ";
}
$duomenys = $text.PHP_EOL;
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------

    include('var.php');
    $servername = $SERVER_NAME;
    $username = $SERVER_USER;
    $password = $SERVER_PASSWORD;
    $dbname = $SERVER_DBNAME;

    $UserID = 1;
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        //die("Connection failed: " . $conn->connect_error);
    } 
$usr = "ramunas";

//-----------------------Nuskaitom is duombazes------------------------------------------------
$sql = "SELECT ID, Name FROM Users WHERE Name = '".$usr."'";
$result = $conn->query($sql);

if ($result->num_rows > 0)
{
  $row = $result->fetch_assoc();
  //echo $row["ID"]."-".$row["Name"];
  $sql = "SELECT Address, Value, Date FROM Termometrai WHERE ID = ".$row["ID"];
  $result = $conn->query($sql);

  if ($result->num_rows > 0)
  {
     $num = 0;
     while($row = $result->fetch_assoc())
     {
        $devices[$num] = $row["Address"]."|".$row["Value"]."|".date('Y-m-d H:i:s', $row["Date"]);
        $num++;
     }
  }
}
$conn->close();
//---------------------------------------------------------------------------------------------

$Sl = "SLEGIS";
$Pwr = "ADC-A1B2C3D4";
$So = "28FF86C961170458";
$Sz = "28FF77F862170410";
$Lk = "28FFDF61C21603DE";
$Sv = "28EE1EF31F16010E";
$Rd = "28FF855FC21603AB";//"28EEDDF822160138";//Rad - "";28FF855FC21603AB,
$Ki = "28EE92811F1602A9";
$Kg = "28FF1742C01604F6";
$Km = "COLD";
$Bl = "28FF855FC21603AB";
$Kl = "28FF233863170408";//
$Kt = "28FFC67FC216037C";

$RdTime = "";

$i = 0;
 while ($i < count($devices)-1) {
   
    $line = explode("|", $devices[$i]);
    $i++;
    
  if($line[0] == $Sl)//Slegis
     $Sl = $line[1]; 
  if($line[0] == $Pwr)//Galia
     $Pwr = $line[1];
  if($line[0] == $Sz)//Siltnamio zeme
     $Sz = $line[1];
  if($line[0] == $So)//Siltnamio oras
     $So = $line[1];
  if($line[0] == $Lk)//Lauke
     $Lk = $line[1];
  if($line[0] == $Sv)//Svetaineje
     $Sv = $line[1];
  if($line[0] == $Rd)//Radiatorius
  {
     $Rd = $line[1]; 
     $RdTime = substr($line[2], 11, 5);
     
  }
  if($line[0] == $Ki)//Katilo iseinamas
    { 
      $Ki = $line[1];
      $LastTime = $line[2];
    }
  if($line[0] == $Km)//Kaminas
     $Km = $line[1]; 
  if($line[0] == $Bl)//Boileris
     $Bl = $line[1];
  if($line[0] == $Kl)//Kolektorius
     $Kl = $line[1]; 
  if($line[0] == $Kg)//Katilo gristamas
     $Kg = $line[1]; 
  if($line[0] == $Kt)//Katilineje
     $Kt= $line[1]; 
     
}

$fp = @fopen("./duomenys/28EE1EF31F16010E", 'r');
fseek($fp, -30000, SEEK_END);
 while (($linex = fgets($fp)) !== false) {
 $line = explode("|", $linex);
  
  if($line[1] > $sekundes)
  {
    $Svv = $Sv-$line[0];
	if(abs($Svv) >= 10)
		$Svv = number_format($Svv, 0);
	else
		$Svv = number_format($Svv, 1);
	
	if($Svv >=0)
		$Svv = "+".$Svv;
    break;
  }
     
}
fclose($fp);

$fp = @fopen("./duomenys/28FF855FC21603AB", 'r');//rad - "./duomenys/"28FF855FC21603AB
fseek($fp, -30000, SEEK_END);
 while (($linex = fgets($fp)) !== false) {
 $line = explode("|", $linex);
  
  if($line[1] > $sekundes)
  {
    $Rdv = $Rd-$line[0];
	if(abs($Rdv) >= 10)
		$Rdv = number_format($Rdv, 0);
	else
		$Rdv = number_format($Rdv, 1);
	
	if($Rdv >= 0)
		$Rdv = "+".$Rdv;
    break;
  }
     
}
fclose($fp);
//$time_end = microtime_float();
//$time = $time_end - $time_start;
//°
//$text = $text.$So.PHP_EOL.$Sz.PHP_EOL.$Sl.PHP_EOL.$Lk.PHP_EOL.$Sv.PHP_EOL.$Rd.PHP_EOL.$Ki.PHP_EOL.$Km.PHP_EOL.$Pwr/*$Bl*/.PHP_EOL.$Kl.PHP_EOL.$Svv.PHP_EOL.$Rdv.PHP_EOL.$RdTime.PHP_EOL;
if($Ki > 82)
  $warning = "1";
else
  $warning = "0";
  
$text = number_format($Sv, 1)."°C ".$Svv."°C".PHP_EOL.number_format($Rd, 1)."°C ".$Rdv."°C ".number_format($Ki, 1)."°C|";
$text = $text.number_format($Sl,0)."hPa D:".number_format($Km, 0)." K:".number_format($Kl, 1). " S:".number_format($So,1)." ".
number_format($Pwr/1000*3, 1)."kW G:".number_format($Kg, 1)."C K:".number_format($Kt, 1)."C|";
$text = $text.$duomenys."|";
$text = $text.number_format($max,1)." ".number_format($min,1)."|";
$text = $text.number_format($Lk, 1)."°C|";
$text = $text.(strtotime($LastTime))."|";//-3600*$hours
$text = $text.$warning;

echo $text;
?>
