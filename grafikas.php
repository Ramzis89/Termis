<?php
session_start();
?>
<html>
<head>
</head>
<style>
{ margin: 0; padding: 0; }

body {
    //background-color: #ccffd1;
}
    html { 
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
    
.WRAPPER {
    background-color: #9100;
    height: 575px;
    width: 975px;
    top: auto;
    margin: -8px;
    
}

input[type=submit]:hover {
    background-color: #0039e6;
}
input[type=submit] {
    width: 110px;
    background-color: #808080;
    color: #545353;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

input[type=datetime-local], select {
    width: 220px;
    color: #545353;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 3px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #77D2FF;
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
    background-color: #27B7FE;
}

#pass1 {
    background-color:rgba(115, 110, 110, 0);
    color:#545353;
    border: none;
    outline:none;
    width: 250px;
    height:40px;
    border: 2px solid #ccc;
}

#pass2 {
    background-color:rgba(115, 110, 110, 0);
    color:#545353;
    border: none;
    outline:none;
    width: 180px;
    height:50px;
    border: 2px solid #ccc;
}
</style>
<body>
<?php
if($_SESSION["security"] === "yes")
{
$usr = $_SESSION["usr"];

$date = new DateTime("now");
$date->setTimezone(new DateTimeZone('Europe/Vilnius'));
$season_time = $date->format('P')." hour";
$season_time = substr($season_time, 0, 3)." hour";
$season_time = "+0 hour";
$season_time_yesterday = $season_time.' -1 day';

$start = $_GET['start'];
$end = $_GET['end'];
$selected_file = $_GET['selected_file'];
$selected_file1 = $_GET['selected_file1'];
$unify = $_GET['unify'];
$first  = $_GET['first'];
$today  = $_GET['today'];

$time_now = date('Y-m-d H:i:s', strtotime($season_time));
echo "
<ul>
  <li><a href=\"./term.php\">Termometrai</a></li>
  <li><a href=\"./esp12.php\">Redaguoti</a></li>
  <li><a href=\"./grafikas.php\">Grafikas</a></li>
  <li><a href=\"./logout.php\">Atsijungti</a></li>
</ul>
";

if(strlen($first) < 1)
{
  $unify = 0;
}

if(strlen($selected_file1) < 4)
{
  $name1 = "";
  $file1 = "";
}


if(strlen($end) < 2 || $today == 1)
{
  $end = date('Y-m-d', intval(strtotime($season_time)))."T".date('H:i', intval(strtotime($season_time)));
}

if(strlen($start) < 2 || $today == 1)
{
  $start = date('Y-m-d', intval(strtotime($season_time_yesterday)))."T".date('H:i', intval(strtotime($season_time_yesterday)));
}

$start_int = strtotime($start);
$end_int = strtotime($end);

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

$sql = "SELECT ID, Name FROM Users WHERE Name = '".$usr."'";
$result = $conn->query($sql);

if ($result->num_rows > 0)
{
  $row = $result->fetch_assoc();
  //Devices Names
  $UserID = $row["ID"];
  $sql = "SELECT Address, Name, Param FROM Names WHERE ID = ".$row["ID"];
  $result = $conn->query($sql);

  if ($result->num_rows > 0)
  {
     $num = 0;
     while($row = $result->fetch_assoc())
     {
        $DevicesNames[$num] = $row["Address"].":".$row["Name"].":".$row["Param"];
        $num++;
        
     }
     //$DevicesNames[$num] = "::";
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

$files = scandir("./".$usr."/duomenys/");

for($k = 0; $k < count($files); $k++)
{
  $failas = $_GET['failas'.$k.''];
  if(strlen($failas) >= 4)
  {
    $failai[$k] = $failas;
  }
}
if(count($failai) == 0)
{
  $failai[0] = $files[2];
}

 //---------------------------------------------------------------------------------------
 //---------------------------Prasideda naujas--------------------------------------------
 //---------------------------------------------------------------------------------------
$unify_checked = " ";
if($unify == 1)
  $unify_checked = "checked";
$today_checked = " ";
if($today == 1)
 $today_checked = "checked";
 
 echo "<form action=\"./grafikas.php\"> 
 <table width=\"100%\">
 <tr>
 <th width=\"10%\" align=\"left\" valign=\"top\">
 <font color=#545353>".$time_now."</font>";
 
 echo "
 <font color=#545353><nobr>Nuo: </font><input type=\"datetime-local\" value= \"".$start."\" name=\"start\" id=pass1 style=\"background-color: #DBEFFF\"></nobr>
 <br><font color=#545353></font><nobr>Iki:&nbsp;&nbsp;&nbsp;</font><input type=\"datetime-local\" value= \"".$end."\" name=\"end\" id=pass1 style=\"background-color: #DBEFFF\"></nobr>
 
 <br><nobr><input type=\"checkbox\" name=\"unify\" value=\"1\" ".$unify_checked."><font color=#545353>Suvienodinti y ašis</font></nobr>
 <br><nobr><input type=\"checkbox\" name=\"today\" value=\"1\" ".$today_checked."><font color=#545353>Šiandien</font></nobr><hr>";

 $params_i = 0;
 $params = "";
 for($j=0; $j < count($files); $j++)
 {
    if(strlen($files[$j]) >= 4)
    {
      for($l = 0; $l < count($DevicesNames)-1; $l++)
      {
        $Device = explode(":", $DevicesNames[$l]);
        if($Device[0] === $files[$j])
          break;
      }
      if($l === count($DevicesNames)-1)//Jei nera jutiklio, bet yra failas -> neatvaizduojam
      {
           continue;
      }

      for($k = 0; $k < count($files); $k++)
      {
        if($failai[$k] == $Device[0])
        {
           $checked = "checked";
           $params = $params."name".$params_i."=".$Device[1]."&file".$params_i."="."./".$usr."/duomenys/".$Device[0]."&";
           $params_i++;
           break;
        }
        else
        {
          $checked = "";
        }
      }
      echo "<nobr> <input type=\"checkbox\" name=\"failas".$j."\" value=\"".$Device[0]."\" ".$checked."><font color=#545353>".$Device[1]."</font></nobr><br>";
    }
 }
 echo "<hr><input type=\"submit\" value=\"Rodyti\">
 </th><th>
 <iframe src=\"./graph2.php?".$params."cnt=".$params_i."&start=".$start_int."&end=".$end_int."&unify=".$unify."\" height=\"650\" width=\"900\" frameBorder=\"0\" scrolling=\"no\" align=\"middle\"></iframe>
 </th></tr>

</table>
<input type=\"text\" value= \"1\" name=\"first\" size=\"1\" style=\"visibility: hidden\"></form> ";
 //---------------------------------------------------------------------------------------
 //---------------------------baigiasi naujas---------------------------------------------
 //---------------------------------------------------------------------------------------
 
}
else
{
  echo "<script>window.location = './index.php'</script>";
}

?>
</body>
</html>
