<html>
<head>
</head>
<body>

<?php
include('var.php');

$UserID = 1;

    $servername = $SERVER_NAME;
    $username = $SERVER_USER;
    $password = $SERVER_PASSWORD;
    $dbname = $SERVER_DBNAME;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        //die("Connection failed: " . $conn->connect_error);
    } 

$date = new DateTime("now");
$date->setTimezone(new DateTimeZone('Europe/Vilnius'));
$season_time = $date->format('P')." hour";
$season_time = substr($season_time, 0, 3)." hour";
$season_time = "+0 hour";

$param0 = 0.12;
$param0 = $_GET['DS'];
$offset = 0;
$text = "";

file_put_contents("./DS.txt",$param0);

if($param0[strlen($param0)-1] != ">")
$param0 = $param0.">";

if (file_exists("customformat.txt") === false)
touch("customformat.txt");

if (file_exists("CustomOut.txt") === false)
touch("CustomOut.txt");

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
      
    //Patikrina ar zinomas
    $sql = "SELECT * FROM Names WHERE Address='".$address."'";
    $result = $conn->query($sql);

    //Jei nezinomas tai uzsiraso
    if($result->num_rows == 0)
    {
      $grl = "0";
      if(strpos($address, "RELAY") !== false)
      {
        $grl = "M|OFF";
        $relay = "1";
      }
      $betkoks = rand(0,999);
      $sql = "INSERT INTO Names (ID, Address, Name, Param) VALUES (".$UserID.", '".$address."', 'Be vardo".$betkoks."', '".$grl."');";
      $result = $conn->query($sql);
    }
    else//Ar reikia grafiko?
    {
      $row = $result->fetch_assoc();
      if(strpos($address, "RELAY") !== false)
      {
        $grafikas_enable = "0";
        $RLY_Control = explode("|", $row["Param"]);
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
        $grafikas_enable = $row["Param"];
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

    if($grafikas_enable == "1")
    {
      file_put_contents("duomenys/".$address, $temperature."|".intval(strtotime($season_time)).PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    //---------------------------------------------------------------------------------------------
    //----------------------Duomenu bazeje issaugoti-----------------------------------------------
    //---------------------------------------------------------------------------------------------
    
    //Patikrinam ar toks termometras yra duomenu bazeje
    $sql = "SELECT * FROM Termometrai WHERE Address='".$address."'";
    $result = $conn->query($sql);

    if($result->num_rows > 0)//Toks termometras jau yra
    {
                $sql = "UPDATE Termometrai SET Date=".intval(strtotime($season_time)).",Value=".$temperature." WHERE Address='".$address."'";    
     }
     else//Jei tokio nera
     {
                $sql = "INSERT INTO Termometrai (ID, Address, Value, Date) VALUES ('".$UserID."', '".$address."', '".$temperature."', '".intval(strtotime($season_time))."')";
     }
     $conn->query($sql);
    //---------------------------------------------------------------------------------------------
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
  /*
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
  
}*/
//-----------------------------------------------------------
$conn->close();
?>

</body>
</html>
