<?php
session_start();
$page = $_SERVER['PHP_SELF'];
$sec = "60";
?>

<html>
<head>
<meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
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


.tooltip {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 80px;
    background-color: #555;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: -40%;
    left: 120%;
    margin-left: -10px;
    opacity: 0;
    transition: opacity 1s;
}

.tooltip .tooltiptext::after {
    content: " ";
    position: absolute;
    top: 50%;
    right: 100%; /* To the left of the tooltip */
    margin-top: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: transparent black transparent transparent;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
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
if($_SESSION["security"] === "yes")
{


echo "
<ul>
  <li><a href=\"./term.php\">Termometrai</a></li>
  <li><a href=\"./esp12.php\">Redaguoti</a></li>
  <li><a href=\"./grafikas.php\">Grafikas</a></li>
  <li><a href=\"./logout.php\">Atsijungti</a></li>
</ul><br>
";
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
$usr = $_SESSION["usr"];

  

$date = new DateTime("now");
$date->setTimezone(new DateTimeZone('Europe/Vilnius'));
$season_time = "+0 hour";

//-------------------------------------------

$season_time_yesterday = $season_time.' -1 day';
$graph_time = date('Y-m-d', intval(strtotime($season_time_yesterday)))."T".date('H:i', intval(strtotime($season_time_yesterday)));

$param0 = 0.12;
$param0 = $_GET['DS'];
$offset = 0;
$text = "";

//---------------------------------------------------------------------------------------------
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
        $devices[$num] = $row["Address"]."|".$row["Value"]."|".date('Y-m-d H:i', $row["Date"]);
        $num++;
     }
  }
}
//---------------------------------------------------------------------------------------------
  //<form method=\"get\" action=\"./logout.php\" style=\"float: right;\">
  // <input type=\"submit\" value=\"Atsijungti\">
  //</form>

  echo "
  <div id=\"WRAPPER\" align=center>
<table>

  
  <tr bgcolor=\"#FF0000\">
          <th> Adresas </th>
          <th> Pavadinimas </th>
          <th> Vertė </th>
          <th> Laikas </th>
          <th> Veiksmas </th>
  </tr>";
 for($i = 0; $i < count($devices); $i++)
 {
   $dat_split = explode("|", $devices[$i]);
   //Neatvaizduoti ID
   if($dat_split[0] !== "ID")
   {   
      $sql = "SELECT * FROM Names WHERE Address = '".$dat_split[0]."'";
      $result = $conn->query($sql);
      
    if ($result->num_rows > 0)
    { 
      $row = $result->fetch_assoc();
      $vardas = $row["Name"];
     }
     else
     {
       $vardas = " ???";
     }
      $time = strtotime($season_time) - strtotime($dat_split[2]);
      $spalva = "";
      if($time < 120)
        $spalva = "white";
      else
       $spalva = "brown";
      
      $NewName = strpos($vardas, "Be vardo");
      if($NewName !== false)
        $NewName = "bgcolor=\"#FFFF00\"";
      else
        $NewName = "";
        
       //Ar ijungtas grafikas?
      if($row["Param"] == "1")
        $grafikas_enable = "Grafikas";
      else
        if(file_exists("./".$usr."/duomenys/".$row["Address"]))
          $grafikas_enable = "Archyvas";
        else
          $grafikas_enable = "";
      //Matavimo vienetai
      if(strpos($dat_split[0], "RELAY") !== false)
      {      
        $RLY_Control = explode("|", $row["Param"]);
        if($RLY_Control[0] === "M")
        {
          if($RLY_Control[1] == "OFF")
          {
            $grafikas_enable = "<a href=\"esp12.php?RLY=".$dat_split[0]."&value=0\">Įjungti</a>";
           if($dat_split[1] == "1")
              $dat_split[1] = "<font color=brown> Išjungta</font>";
            else
              $dat_split[1] = "<font color=brown>Išjungiama</font>";
          }
          else if($RLY_Control[1] == "ON")
          {
            $grafikas_enable = "<a href=\"esp12.php?RLY=".$dat_split[0]."&value=1\">Išjungti</a>";
            if($dat_split[1] == "0")
              $dat_split[1] = "<font color=blue>Įjungta</font>";
            else
              $dat_split[1] = "<font color=blue>Įjungiama</font>";
          }
        }
        else if($RLY_Control[0] === "T")//Jei ijungtas rly control
        {
          if($dat_split[1] == "1")
            $dat_split[1] = "<font color=brown> Išjungta</font>";
          else
            $dat_split[1] = "<font color=blue>Įjungta</font>";
            
            for($k=0; $k < count($DevicesNames)-1; $k++)
            {
              $SavedDevicesName = explode(":", $DevicesNames[$k]);
              if($SavedDevicesName[0] == $RLY_Control[1])
                break;
            }
            $grafikas_enable = $SavedDevicesName[1]." > ".$RLY_Control[2]."  °C";
        }
        else if($RLY_Control[0] === "L")
        {
          if($dat_split[1] == "1")
            $dat_split[1] = "<font color=brown> Išjungta</font>";
          else
            $dat_split[1] = "<font color=blue>Įjungta</font>";
            
          $RLY_Control[1] = str_replace(".", ":", $RLY_Control[1]);
          $RLY_Control[2] = str_replace(".", ":", $RLY_Control[2]);
          $grafikas_enable = "Įj. ".$RLY_Control[1]."; Iš. ".$RLY_Control[2];
        }

      }
      else if(strpos($dat_split[0], "ADC") !== false)
       $dat_split[1] = $dat_split[1]." mV";
      else
        $dat_split[1] = number_format($dat_split[1], 1, ",", "")." °C";
        
     //Jei yra duomenu failas, priskirti nuoroda

     if(file_exists("./".$usr."/duomenys/".$row["Address"]))
     {
       $grafikas_enable = "<a href=\"grafikas.php?name=".$row["Name"]."&failas0=".$row["Address"]."&start=".$graph_time."\">".$grafikas_enable."</a>";
       $filesize = filesize("./".$usr."/duomenys/".$row["Address"])/1024;
       if($filesize > 1024)
       {
         $filesize = number_format($filesize/1024, 2);
         $filesize = number_format($filesize, 2);
         $filesize = $filesize." MB";
        }
       else
       {
         $filesize = number_format($filesize, 2);
         $filesize = $filesize." kB";
       }
       
       $grafikas_enable = "<div class=\"tooltip\">".$grafikas_enable."<span class=\"tooltiptext\">".$filesize."</span></div>";
     }     
     //Prie vardo pridedam ESP id
     //$dat_split[0] = "<div class=\"tooltip\">".$dat_split[0]."<span class=\"tooltiptext\">".$dat_split[3]."</span></div>";
     
     echo "<tr> <th>".$dat_split[0]."</th> 
     <th ".$NewName.">".$vardas."</th>
     <th>".$dat_split[1]."</th>
     <th><font color=\"$spalva\">".$dat_split[2]."</font></th>
     <th>".$grafikas_enable."</th></tr>";
   }
}
  echo "</table><br></div>";
  if (file_exists("./".$usr."/schema.php") == true)
  echo "<iframe src=\"/".$usr."/schema.php\" height=\"800\" width=\"1100\" frameBorder=\"0\" scrolling=\"no\" align=\"middle\"></iframe>";
 // <form method=\"get\" action=\"./esp12.php\">
 //   <input type=\"submit\" value=\"Redaguoti\">
//</form>  
}
else
{
  echo "<script>window.location = './index.php'</script>";
}
$conn->close();
?>



</body>
</html>
