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
for($cnt = 144; $cnt > 0; $cnt--)
{
  $vid = 0;
  for($cntv = 0; $cntv < $vid_sk; $cntv++)
  {
  if($datay[$array_size-$cnt*$vid_sk-$cntv] > $vid)
    $vid = $datay[$array_size-$cnt*$vid_sk-$cntv];
  }
  $array[$j] = $vid;
  $j++;
  if($vid > 100)
     $ampFlag = 1;
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
$text = $text.PHP_EOL;


$Sl = "SLEGIS";
$Pwr = "ADC-A1B2C3D4";

$fp = @fopen("../vardenis/esp1.txt", 'r');
 while (($linex = fgets($fp)) !== false) {
 $line = explode("|", $linex);
  
  
  if($line[0] == $Sl)//Slegis
     $Sl = $line[1]; 
  else if($line[0] == $Pwr)//Galia
     $Pwr = $line[1];
     
}
fclose($fp);

$So = "28ff86c961170458";
$Sz = "28ff77f862170410";
$Lk = "28FFDF61C21603DE";
$Sv = "28EE1EF31F16010E";
$Rd = "28FF855FC21603AB";//Rad - "28EEDDF822160138";
$Ki = "28EE92811F1602A9";
$Km = "cold-C7268D6A";
$Bl = "28FF855FC21603AB";
$Kl = "28FFC67FC216037C";//Kolektorius 28FF593763170442";
$RdTime = "";

$fp = @fopen("./esp1.txt", 'r');
 while (($linex = fgets($fp)) !== false) {
 $line = explode("|", $linex);
  
  if($line[0] == $So)//Siltnamio oras
     $So = $line[1];
  if($line[0] == $Sz)//Siltnamio zeme
     $Sz = $line[1];
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
     $Ki = $line[1];
  if($line[0] == $Km)//Kaminas
     $Km = $line[1]; 
  if($line[0] == $Bl)//Boileris
     $Bl = $line[1];
  if($line[0] == $Kl)//Kolektorius
     $Kl = $line[1]; 
     
}
fclose($fp);

$fp = @fopen("./duomenys/28EE1EF31F16010E", 'r');
fseek($fp, -30000, SEEK_END);
 while (($linex = fgets($fp)) !== false) {
 $line = explode("|", $linex);
  
  if($line[1] > $sekundes)
  {
    $Svv = $line[0];
    break;
  }
     
}
fclose($fp);

$fp = @fopen("./duomenys/28FF855FC21603AB", 'r');//rad - "./duomenys/28EEDDF822160138"
fseek($fp, -30000, SEEK_END);
 while (($linex = fgets($fp)) !== false) {
 $line = explode("|", $linex);
  
  if($line[1] > $sekundes)
  {
    $Rdv = $line[0];
    break;
  }
     
}
fclose($fp);
//$time_end = microtime_float();
//$time = $time_end - $time_start;
//°
//$text = $text.$So.PHP_EOL.$Sz.PHP_EOL.$Sl.PHP_EOL.$Lk.PHP_EOL.$Sv.PHP_EOL.$Rd.PHP_EOL.$Ki.PHP_EOL.$Km.PHP_EOL.$Pwr/*$Bl*/.PHP_EOL.$Kl.PHP_EOL.$Svv.PHP_EOL.$Rdv.PHP_EOL.$RdTime.PHP_EOL;
$text = "L:".number_format($Lk, 1)." S:".number_format($Sv, 1)." R:".number_format($Rd, 1)." G:".number_format($Pwr, 0)."W";
echo $text;
?>