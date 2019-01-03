<html>
<head>
</head>
<body>

<?php
$date = new DateTime("now");
$date->setTimezone(new DateTimeZone('Europe/Vilnius'));
$season_time = $date->format('P')." hour";
$season_time = substr($season_time, 0, 3)." hour";

$param0 = 0.12;
$param0 = $_GET['DS'];
$offset = 0;
$text = "";

//file_put_contents("./DS.txt",$param0);

if($param0[strlen($param0)-1] != ">")
$param0 = $param0.">";

$DS18B20 = "DS18B20.txt";

if (file_exists($DS18B20) === false)
touch($DS18B20);

if (file_exists("esp1.txt") === false)
touch("esp1.txt");

if (file_exists("customformat.txt") === false)
touch("customformat.txt");

if (file_exists("CustomOut.txt") === false)
touch("CustomOut.txt");

$handle = fopen($DS18B20, "r");
$Devices = fread($handle, filesize($DS18B20)+1024);
fclose($handle);
//------------Nuskaitom ID---------------
$pos0 = strpos($param0, "ID:");
if($pos0 !== false)
{
  $pos1 = strpos($param0, ";", $pos0);
  $pos2 = strpos($param0, ">", $pos0);
  if($pos1 !== false)
    if($pos1 < $pos2)
      $pos2 = $pos1;
  $id = substr($param0, $pos0+3, $pos2-$pos0-3);
  $l = $pos2-$pos0-3;
}
//-------------------------------------------
$pos0 = strpos($param0, ";", $offset);
while($offset !== 1)
{
if($pos0 !== false)
{
  $pos1 = strpos($param0, ":", $pos0);
  if($pos1 !== false)
  {
    //Nuskaito adresa
    $address = substr($param0, $pos0+1, $pos1-$pos0-1);
    //Jei ID -> neissaugoti
    if($address === "ID")
    {
      //Tesiam toliau
      $pos0 = strpos($param0, ";", $pos1);
      if($pos0 == 0)
      {
        $pos0 = strpos($param0, ">", $pos1);
      }
    }
    else
    {
    $grafikas_enable = "0";
    //Jei adresas yra RELAY arba ADC, pridedam ESP id
    if($address === "RELAY" || $address === "ADC" || $address === "cold")
      $address = $address."-".$id;
      
    $text = $address."|";
    //Patikrina ar zinomas
    $name0 = strpos($Devices, $address);
    //Jei nezinomas tai uzsiraso
    if($name0 === false)
    {
      $grl = "0";
      if(strpos($address, "RELAY") !== false)
      {
        $grl = "M|OFF";
        $relay = "1";
      }
      $betkoks = rand(0,999);
      $handle = fopen($DS18B20, "a");
      fwrite($handle, $address.":Be vardo".$betkoks.":".$grl."\n");
      fclose($handle);
    }
    else//Ar reikia grafiko?
    {
      $name0 = strpos($Devices, ":", $name0+1)+1;
      $name0 = strpos($Devices, ":", $name0+1)+1;
      if(strpos($address, "RELAY") !== false)
      {
        $DevicesNames = explode("\n", $Devices);
        for($j = 0; $j < count($DevicesNames)-1; $j++)
        {
          $Device = explode(":", $DevicesNames[$j]);
          if(strpos($Device[0], "RELAY-".$id) !== false)
          break;
        }
        //echo "<".$Device[2].">";
        $grafikas_enable = "0";
        $RLY_Control = explode("|", $Device[2]);
        if($RLY_Control[0] === "M")
        {
          if($RLY_Control[1] === "ON")
          {
            $relay = "0";
          }
          else if($RLY_Control[1] === "OFF")
          {
            $relay = "1";
          }
        }
        else if($RLY_Control[0] === "T")
        {
          $relay = "2";       
        }
        else if($RLY_Control[0] === "L")
        {
          $startText = explode(".", $RLY_Control[1]);
          $endText = explode(".", $RLY_Control[2]);
          $start = intval($startText[0]) * 60 + intval($startText[1]);
          $end = intval($endText[0]) * 60 + intval($endText[1]);
          $nowText = date("H.i",strtotime($season_time)); 
          $nowText = explode(".", $nowText);
          $now = intval($nowText[0]) * 60 + intval($nowText[1]);
          if($now >= $start && $now < $end)
            $relay = "0";
          else
            $relay = "1";
        }
        
      }
      else
      {
        $grafikas_enable = $Devices[$name0];
      }
    }
    //Nuskaito temperatura
    $pos0 = strpos($param0, ";", $pos1);
    if($pos0 == 0)
    {
      $pos0 = strpos($param0, ">", $pos1);
    }
    $temperature = substr($param0, $pos1+1, $pos0-$pos1-1);
    if($address !== "IP")
      $temperature = intval($temperature)/10000;
    if($temperature > 4000)
      $temperature = $temperature - 4096;
    //Suformuoja prietaiso eilute
    $text = $text.$temperature."|";
    $text = $text.date('Y-m-d H:i:s', intval(strtotime($season_time)));
    //$text = $text."|".$id;
    $text = $text.PHP_EOL;
    echo $text;
    if($grafikas_enable == "1")
    {
      file_put_contents("duomenys/".$address, $temperature."|".intval(strtotime($season_time)).PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    //Nuskaito senas vertes
    $handle = fopen("./esp1.txt", "r");
    $oldValues = fread($handle, filesize("./esp1.txt")+1024);
    fclose($handle);
    //Tikrina ar yra toks prietaisas
    $pos2 = strpos($oldValues, $address);
    if($pos2 !== false)
    {
      //Jei toks prietaisas yra
      $pos1 = strpos($oldValues, "\n", $pos2)+1;
      if($pos1 < $pos2)
      {
        $pos1 = strlen($oldValues);
       }
       
      $NewValue = substr($oldValues, 0, $pos2).$text.substr($oldValues, $pos1);
      $handle = fopen("./esp1.txt", "w");
      $len = fwrite($handle, $NewValue);
      fclose($handle);
    }
    else
    {
      //Jei tokio prietaiso nera
      $handle = fopen("./esp1.txt", "a");
      $len = fwrite($handle, $text);
      fclose($handle);
    }
    }
  }
}
$pos0 = strpos($param0, ";", $pos0);
if($pos0 == 0)
{
  $offset= 1;
}

}
if($relay === "2")
  echo "RLY_ADR=".$RLY_Control[1].";RLY_VAL=".$RLY_Control[2].";".PHP_EOL;
else
  echo "RELAY=".$relay.PHP_EOL;
  
//-----------------Find duplicates---------------------------
//Nuskaitom faila
 $found = 0;  
 $handle = fopen("./esp1.txt", "r");
 $Values = fread($handle, filesize("./esp1.txt")+1024);
 fclose($handle);
 $Devices = explode("\n", $Values);
 for($i = 0; $i < count($Devices)-2; $i++)
 {
    $cmp1 = explode("|", $Devices[$i]);
    for($j = $i+1; $j < count($Devices)-1; $j++)
    {
      $cmp2 = explode("|", $Devices[$j]);
      if($cmp1[0] == $cmp2[0])//rado duplikata
      {
         unset($Devices[$j]);
         $found = 1;
      }
    }
 }
 if($found == 1)
 {
    $handle = fopen("./esp1.txt", "w");
    for($i=0; $i < count($Devices); $i++)
       if(strlen($Devices[$i]) > 0)
       {
          fwrite($handle, $Devices[$i].PHP_EOL);
       }
    fclose($handle);
 }
//-----------------Custom faila rasyti-----------------------
$handle = fopen("customformat.txt", "r");
$CustomEnable = fgets($handle);
$CustomFormat = fgets($handle);
fclose($handle);

if(($CustomEnable[0] === "T") && (strlen($CustomFormat) > 0))
{
  $handle = fopen("./esp1.txt", "r");
  $SavedDataValues = fread($handle, filesize("./esp1.txt")+1024);
  fclose($handle);
  
  $handle = fopen("./".$DS18B20, "r");
  $SavedDeviceNames = fread($handle, filesize("./".$DS18B20)+1024);
  fclose($handle);
  //----------------Vardas-------------------------------
  while(strpos($CustomFormat, "vardas{") !== false)
  {
    $pos0 = strpos($CustomFormat, "vardas{");
    $pos1 = strpos($CustomFormat, "{", $pos0)+1;
    $pos2 = strpos($CustomFormat, "}", $pos1);
    $adr = substr($CustomFormat, $pos1, $pos2-$pos1);
    if(strlen($adr) > 5)
    {
      $pos3 = strpos($SavedDeviceNames,$adr);
      if($pos3 !== false)
      {
        $pos4 = strpos($SavedDeviceNames,":", $pos3)+1;
        $pos5 = strpos($SavedDeviceNames,":", $pos4);
        $name = substr($SavedDeviceNames, $pos4, $pos5-$pos4);
        if(strlen($name) > 0)
        {
          $CustomFormat = substr($CustomFormat, 0, $pos0).$name.substr($CustomFormat, $pos2+1);
        }
      }
      
    }
  }
  //----------------Verte-------------------------------
  while(strpos($CustomFormat, "verte{") !== false)
  {
    $pos0 = strpos($CustomFormat, "verte{");
    $pos1 = strpos($CustomFormat, "{", $pos0)+1;
    $pos2 = strpos($CustomFormat, "}", $pos1);
    $adr = substr($CustomFormat, $pos1, $pos2-$pos1);
    if(strlen($adr) > 5)
    {
      $pos3 = strpos($SavedDataValues,$adr);
      if($pos3 !== false)
      {
        $pos4 = strpos($SavedDataValues,"|", $pos3)+1;
        $pos5 = strpos($SavedDataValues,"|", $pos4);
        $name = substr($SavedDataValues, $pos4, $pos5-$pos4);
        $name = number_format($name, 2);
        if(strlen($name) > 0)
        {
          $CustomFormat = substr($CustomFormat, 0, $pos0).$name.substr($CustomFormat, $pos2+1);
        }
      }
      
    }
  }
  //----------------Data-------------------------------
  while(strpos($CustomFormat, "data{") !== false)
  {
    $pos0 = strpos($CustomFormat, "data{");
    $pos1 = strpos($CustomFormat, "{", $pos0)+1;
    $pos2 = strpos($CustomFormat, "}", $pos1);
    $adr = substr($CustomFormat, $pos1, $pos2-$pos1);
    if(strlen($adr) > 5)
    {
      $pos3 = strpos($SavedDataValues,$adr);
      if($pos3 !== false)
      {
        $pos4 = strpos($SavedDataValues,"|", $pos3)+1;
        $pos5 = strpos($SavedDataValues,"|", $pos4)+1;
        $pos6 = strpos($SavedDataValues," ", $pos5);
        $name = substr($SavedDataValues, $pos5, $pos6-$pos5);
        if(strlen($name) > 0)
        {
          $CustomFormat = substr($CustomFormat, 0, $pos0).$name.substr($CustomFormat, $pos2+1);
        }
      }
      
    }
  }
  //----------------Laikas-------------------------------
  while(strpos($CustomFormat, "laikas{") !== false)
  {
    $pos0 = strpos($CustomFormat, "laikas{");
    $pos1 = strpos($CustomFormat, "{", $pos0)+1;
    $pos2 = strpos($CustomFormat, "}", $pos1);
    $adr = substr($CustomFormat, $pos1, $pos2-$pos1);
    if(strlen($adr) > 5)
    {
      $pos3 = strpos($SavedDataValues,$adr);
      if($pos3 !== false)
      {
        $pos4 = strpos($SavedDataValues," ", $pos3)+1;
        $pos5 = strpos($SavedDataValues,"\n", $pos4);
        $name = substr($SavedDataValues, $pos4, $pos5-$pos4);
        if(strlen($name) > 0)
        {
          $CustomFormat = substr($CustomFormat, 0, $pos0).$name.substr($CustomFormat, $pos2+1);
        }
      }
    }
  }
    //----------------Nl-------------------------------
    $pos0 = strpos($CustomFormat, "nl{}");
    if($pos0 !== false)
    {
      $CustomFormat = str_replace("nl{}", "\n", $CustomFormat);
    }
    file_put_contents("./CustomOut.txt",$CustomFormat);
  
}
//-----------------------------------------------------------
?>

</body>
</html>
