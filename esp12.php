<?php
session_start();
?>
<html>
<head>
<style>

{ margin: 0; padding: 0; }

    html { 
        background: url('background.jpg') no-repeat center center fixed; 
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }

.WRAPPER {
    background-color: #9100;
    height: 575px;
    width: 975px;
    background-image: background.jpg);
    top: auto;
    margin: -8px;
    
}

input[type=select], select {
    width: 150px;
    padding: 6px;
    margin: 8px 0;
    display: inline-block;
    border: 3px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}


input[type=password], select {
    width: 150px;
    padding: 6px;
    margin: 8px 0;
    display: inline-block;
    border: 3px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
input[type=submit]:hover {
    background-color: #0039e6;
}
input[type=submit] {
    width: 110px;
    background-color: #808080;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    text-align: left;
    padding: 6px;
}

tr:nth-child(even) {background: #4CAF50}
tr:nth-child(odd) {background: #449d48}


th {
    color: white;
}

#resize{
    resize:both;
}

#pass2 {
    background-color:rgba(115, 110, 110, 0);
    color:white;
    border: none;
    outline:none;
    width: 120px;
    height:40px;
    border: 3px solid #ccc;
}
#time1 {
    background-color:rgba(115, 110, 110, 0);
    color:white;
    border: none;
    outline:none;
    width: 80px;
    height:30px;
    border: 3px solid #ccc;
    transition:height 1s;
    -webkit-transition:height 1s;
    -moz-border-radius: 7px;
    border-radius: 7px;
}

#pass1 {
    background-color:rgba(115, 110, 110, 0);
    color:white;
    border: none;
    outline:none;
    width: 50px;
    height:30px;
    border: 3px solid #ccc;
    transition:height 1s;
    -webkit-transition:height 1s;
    -moz-border-radius: 7px;
    border-radius: 7px;
}
#pass1:focus {
    height:50px;
    font-size:16px;
}
#custtext {
    background-color:rgba(115, 110, 110, 0);
    color:white;
    border: none;
    outline:none;
    height:30px;
    width:10%;
    font-size:14px;
    border: 3px solid #ccc;
    transition:height 1s;
    -webkit-transition:height 1s;
    -moz-border-radius: 7px;
    border-radius: 7px;
}
#custtext:focus {
    height:30px;
    width:100%;
    font-size:14px;
}

#pass {
    background-color:rgba(115, 110, 110, 0);
    color:white;
    border: none;
    outline:none;
    height:30px;
    border: 3px solid #ccc;
    transition:height 1s;
    -webkit-transition:height 1s;
    -moz-border-radius: 7px;
    border-radius: 7px;
}
#pass:focus {
    height:50px;
    font-size:16px;
}
input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(2); /* IE */
  -moz-transform: scale(2); /* FF */
  -webkit-transform: scale(2); /* Safari and Chrome */
  -o-transform: scale(2); /* Opera */
  padding: 10px;
}

/* Might want to wrap a span around your checkbox text */
.checkboxtext
{
  /* Checkbox text */
  font-size: 110%;
  display: inline;
}


ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #2f6a31;
}

li {
    float: left;
}

li a {
    display: inline-block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    width: 100%;
    font-weight: bold;
}

li a:hover {
    background-color: #1f4720;
}

</style>
</head>
<body>

<?php

$esp12 = "esp12.php";


  function psw_crypt( $string, $action = 'e' ) {
    include('var.php');
    $secret_key = $SECURITY_KEY;
    $secret_iv = $SECURITY_IV;
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}


    
if($_SESSION["security"] === "yes")
{
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
	
echo "
<ul>
  <li><a href=\"./term.php\">Termometrai</a></li>
  <li><a href=\"./".$esp12."\">Redaguoti</a></li>
  <li><a href=\"./grafikas.php\">Grafikas</a></li>
  <li><a href=\"./logout.php\">Atsijungti</a></li>
</ul>
";

$usr = $_SESSION["usr"];

$sql = "SELECT * FROM Users WHERE Name = '".$usr."'";
$result = $conn->query($sql);

if ($result->num_rows > 0)
{
  $row = $result->fetch_assoc();
  //Devices Names
  $UserID = $row["ID"];
  $sql = "SELECT * FROM Names WHERE ID = ".$UserID;
  $result = $conn->query($sql);

  if ($result->num_rows > 0)
  {
     $num = 0;
     while($row = $result->fetch_assoc())
     {
        $DevicesNames[$num] = $row["Address"].":".$row["Name"].":".$row["Param"];
        $num++;
        
     }
  }
  //Devices values
  $sql = "SELECT * FROM Termometrai WHERE ID = ".$UserID;
  $result = $conn->query($sql);

  if ($result->num_rows > 0)
  {
     $num = 0;
     while($row = $result->fetch_assoc())
     {
		$DevicesValues[$num] = $row["Address"]."|".$row["Value"]."|".$row["Date"];
        $num++;
     }
  }
}
$msg = $_GET['msg'];
$oldpsw = $_GET['oldpsw'];
$newpsw1 = $_GET['newpsw1'];
$newpsw2 = $_GET['newpsw2'];
$CustomEnable = $_GET['CustomEnable'];
$CustomFormat = $_GET['CustomFormat'];

//Custom format failas
if(strlen($CustomEnable) > 0)
{
  if($CustomEnable === "T")
  {
    file_put_contents("./".$usr."/customformat.txt", $CustomEnable.PHP_EOL.$CustomFormat, LOCK_EX);
  }
  else if($CustomEnable === "N")
  {
    file_put_contents("./".$usr."/customformat.txt", $CustomEnable.PHP_EOL.$CustomFormat, LOCK_EX);
  }
}
else
{
 $CustomFormat = "";
 if(file_exists("./".$usr."/customformat.txt") !== false)
 if(filesize("./".$usr."/customformat.txt") > 0)
 {
   $fp = @fopen("./".$usr."/customformat.txt", 'r');
   $CustomEnable = fgets($fp);
   $CustomFormat = fgets($fp);
   fclose($fp);
 }
 }

//-----------------------------
if(strlen($oldpsw) > 0)
{
$fp = @fopen("./".$usr."/psw.txt", 'r');
$savedpsw = @fread($fp, filesize("./".$usr."/psw.txt")+1);
fclose($fp);
$savedpsw = psw_crypt($savedpsw, 'd');

if($savedpsw !== $oldpsw)
  echo "<h1><font color=white>Senas slaptažodis neatitinka!</font></h1>";
else
{
  if(strlen($newpsw1) < 4)
  {
    echo "<h1><font color=white>Per trumpas naujas slaptažodis! Ne mažiau 4 simbolių!</font></h1>";
  }
  else
  {
    if($newpsw1 === $newpsw2)
    {
      $savedpsw = psw_crypt($newpsw1, 'e');
      
      $fp = @fopen("./".$usr."/psw.txt", 'w');
      fwrite($fp, $savedpsw);
      fclose($fp);
      echo "<script>window.location = './".$esp12."?msg=1'</script>";
    }
    else
    {
      echo "<h1><font color=white>Nesutampa naujas slaptažodis!</font></h1>";
    }
  }
  }
}

$Rname = $_GET['RLY'];
$Rvalue = $_GET['value'];

for($i=0; $i<count($DevicesNames); $i++)
  if(strpos($DevicesNames[$i], $Rname) !== false)
  {
    $Device = explode(":", $DevicesNames[$i]);
    $relay = $Device[2];
    break;
  }

if(strlen($Rvalue) !== 0)
{
  if($Rvalue === "0")
  {
    $Rvalue = "M|ON";
    echo "<h1><font color=white>".$Device[1]."(".$Rname.") relė įjungta!</font></h1>";
  }
  else
  {
    $Rvalue = "M|OFF";
    echo "<h1><font color=white>".$Device[1]."(".$Rname.") relė išjungta!</font></h1>";
   }
   
  $sql = "UPDATE Names SET Param = '".$Rvalue."' WHERE Name = '".$Rname."'";
  $result = $conn->query($sql);
}
else
{
$date = new DateTime("now");
$date->setTimezone(new DateTimeZone('Europe/Vilnius'));
$season_time = $date->format('P')." hour";
$season_time = substr($season_time, 0, 3)." hour";

$param0 = 0.12;
$eil = $_GET['eil'];
$vardas = $_GET['vardas'];
$istrinti = $_GET['DeleteThermometer'];

$text = "";
$pakeista = 0;

$name = $_GET['name0'];
//Issaugom visas vertes
if(strlen($name) > 0)
{
  for($i=0; $i<count($DevicesNames); $i++)
  {
    $name = $_GET['name'.$i.''];
    $graph = $_GET['graph'.$i.''];
    $rly_control = $_GET['rly_control'.$i.''];
    $rly_value = $_GET['rly_valueT'.$i.''];
    $rly_value0 = $_GET['rly_value0'.$i.''];
    $rly_value1 = $_GET['rly_value1'.$i.''];
    $rly_name = $_GET['rly_name'.$i.''];
    $rly_activate = $_GET['rly_activate'.$i.''];
    if($graph == "on")
      $graph = "1";
    else
      $graph = "0";
    
    $Device = explode(":", $DevicesNames[$i]);
    if(strpos($Device[0], "RELAY") !== false)
    {
      if($rly_activate == "M")
      {
        $graph = "M|OFF";
      }
      else if($rly_activate == "T")
      {
        if((strlen($rly_name) > 0) && (strlen($rly_value) > 0))
        {
          $graph = "T|".$rly_name."|".$rly_value;
        }
        else
        {
          $graph = "T|28EEFFFFFFFFFFFF|30";
        }
      }
      else if($rly_activate == "L")
      {
        if((strlen($rly_value0) > 0) && (strlen($rly_value1) > 0))
        {
          $rly_value0 = str_replace(":", ".", $rly_value0);
          $rly_value1 = str_replace(":", ".", $rly_value1);
          $graph = "L|".$rly_value0."|".$rly_value1;
        }
        else
        {
          $graph = "L|08.00|09.00";
        }
      }
    }
	//Atnaujinam irasa
    $sql = "UPDATE Names SET Name = '".$name."', Param = '".$graph."' WHERE Address = '".$Device[0]."'";
	echo "   <".$sql.">   ";
	$result = $conn->query($sql);
  }
  
	$pakeista = 1;
	
	$sql = "SELECT * FROM Names WHERE ID = ".$UserID;
	$result = $conn->query($sql);

	if ($result->num_rows > 0)
	{
		$num = 0;
		while($row = $result->fetch_assoc())
		{
			$DevicesNames[$num] = $row["Address"].":".$row["Name"].":".$row["Param"];
			$num++;
		}
	}
}
   if($pakeista === 1)
   {
     echo "<h1><font color=white>Išsaugota!</font></h1>";
   }


$delete = $_GET['Delete'];
if(strlen($delete) > 0)
{
	//Istrinam is Names lenteles, $delete - termometro adresas
	$sql = "DELETE FROM Names WHERE Address = '".$delete."'";
	$result = $conn->query($sql);
    
	//Istrinam is Termometrai lenteles
	$sql = "DELETE FROM Termometrai WHERE Address = '".$delete."'";
	$result = $conn->query($sql);
	
	//Istrinam duomenis
	if(file_exists("./".$usr."/duomenys/".$delete))
	{
		$delete = "./".$usr."/duomenys/".$delete;
		unlink($delete);
	}
}
 //Breziama pagrindine lentele
  echo "<br><form action=\"./".$esp12."\">
  <table>
  <tr><th>Adresas</th><th>Pavadinimas</th><th>  </th><th></th></tr>";
  for($i = 0; $i < count($DevicesNames); $i++)
  {
    $device = explode(":", $DevicesNames[$i]);//address:name:data
    $checbox = "";
    if($device[2] == "1")
      $checbox = "<input type=\"checkbox\" name=\"graph".$i."\"".$checbox." checked/>Grafikas";
    else
      $checbox = "<input type=\"checkbox\" name=\"graph".$i."\"".$checbox."/>Grafikas";
    
    if(strpos($device[0], "RELAY") !== false)//rly start
      {  
        $RLY_Values = explode("|", $device[2]);
        
        $rly_select = "<select name=\"rly_name".$i."\" id=pass2 style=\"background-color: #4CAF50\">";
        for($j=0; $j<count($DevicesValues); $j++)
        {
          $SavedDevice = explode("|", $DevicesValues[$j]);//28FFC67FC216037C|19|2018-01-09 10:41:15
          
          if($SavedDevice[0][0] === "2")//Randam termometro adresa
          {
            for($k=0; $k < count($DevicesNames); $k++)//Randam termometro varda pagal adresa
            {
             $DeviceName = explode(":", $DevicesNames[$k]);
               if($DeviceName[0] == $SavedDevice[0])
                 break;
            }
            
             if(($RLY_Values[1] == $SavedDevice[0]) && (count($RLY_Values) > 1))
               $rly_select = $rly_select."<option value=".$SavedDevice[0]." selected>".$DeviceName[1]."</option>";
             else
               $rly_select = $rly_select."<option value=".$SavedDevice[0].">".$DeviceName[1]."</option>";
          }
        }
        $rly_select = $rly_select."</select> ";
        $rly_activate = $RLY_Values[0];//M L T
        if($rly_activate==="M")//RELAY-A8541D6F:Mygtukas:M|0
        {
        $selM = "selected";
        $selL = "";
        $selT = "";
        }
        else if($rly_activate==="L")//RELAY-A8541D6F:Mygtukas:L|05.00|21.00
        {
        $selM = "";
        $selL = "selected";
        $selT = "";
        }
        else if($rly_activate==="T")//RELAY-A8541D6F:Mygtukas:T|21|28FF1742C01604F6
        {
        $selM = "";
        $selL = "";
        $selT = "selected";
        }
        
        $rly_func = "<select name=\"rly_activate".$i."\"id=pass2 style=\"background-color: #4CAF50\"><option value=M ".$selM.">Mygtukas</option><option value=L ".$selL.">Laikas</option><option value=T ".$selT.">Temperatūra</option></select>";
        
        //$time_split = explode("|", $device[2]);
        $time_split[1] = str_replace(".", ":", $RLY_Values[1]);
        $time_split[2] = str_replace(".", ":", $RLY_Values[2]);
        
        if($rly_activate == "M")
        {
          $checbox = $rly_func.$rly_select." > <input type=\"text\" name=\"rly_valueT".$i."\" id=pass1 > °C";
          $checbox = $checbox." | Įj.<input type=\"time\" value = \"\"name=\"rly_value0".$i."\" id=time1>Iš.<input type=\"time\" value=\"\" name=\"rly_value1".$i."\" id=time1>";
        }
        else if($rly_activate == "L")
        {
          $checbox = $rly_func.$rly_select." > <input type=\"text\" name=\"rly_valueT".$i."\" id=pass1> °C";
          $checbox = $checbox." | Įj.<input type=\"time\" value = \"".$time_split[1]."\"name=\"rly_value0".$i."\" id=time1>Iš.<input type=\"time\" value=\"".$time_split[2]."\" name=\"rly_value1".$i."\" id=time1>";
        }
        else if($rly_activate == "T")
        {
          $checbox = $rly_func.$rly_select." > <input type=\"text\" name=\"rly_valueT".$i."\" id=pass1 value=".$RLY_Values[2]."> °C";
          $checbox = $checbox." | Įj.<input type=\"time\" name=\"rly_value0".$i."\" id=time1>Iš.<input type=\"time\" name=\"rly_value1".$i."\" id=time1>";
        }
        
      }//rly end
    echo "<tr><th>".$device[0]."</th>
    <th><input type=\"text\" name=\"name".$i."\" value=\"".$device[1]."\" id=pass></th>
    <th>".$checbox."</th>
    <th><a href=\"".$esp12."?Delete=".$device[0]."\">Pašalinti</a></th>";
  }
  $selT = "";
  $selN = "";

  if($CustomEnable[0] === "T")
    $selT = "selected";
 else if($CustomEnable[0] === "N")
    $selN = "selected";

    
  echo "</table>  <br> <br><font color=white><b>
  Pasirinktinių duomenų rašymas į failą
 <select name=\"CustomEnable\" id=pass2 style=\"background-color: #2D70B4\" title=\""."http://".$_SERVER['HTTP_HOST']."/".$usr."/CustomOut.txt\" >
    <option value=\"T\" ".$selT.">Įjungta</option>
    <option value=\"N\" ".$selN.">Išjungta</option>
  </select> 
     <input type=\"text\" name=\"CustomFormat\" id=custtext value=\"".$CustomFormat."\" title=\"vardas{id}\nverte{id}\nlaikas{id}\ndata{id}\nnl{}\n\">
   </font></b>
   <br><input type=\"submit\" value=\"Išsaugoti\">
         </form>
                <form action=\"./".$esp12."\">
                <font color=white><b>
      Pakeisti slaptažodį:<br>
     Senas slaptažodis: <input type=\"password\" name=\"oldpsw\" id=pass>
     Naujas slaptažodis: <input type=\"password\" name=\"newpsw1\" id=pass> pakartoti naują: <input type=\"password\" name=\"newpsw2\" id=pass>
     <input type=\"submit\" value=\"Pakeisti\"></font></b></form>";
   
        
   if($msg === "1")
   {
     echo "<h1><font color=white>Naujas slaptažodis išsaugotas!</font></h1>";
   }
   

}
}
else
{
  echo "<script>window.location = './index.php'</script>";
}
$conn->close();
?>

</body>
</html>

