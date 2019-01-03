<?php
session_start();
?>
<html>
<head>
</head>
<style>
{ margin: 0; padding: 0; }

body {
    //background-color: #3399ff;
}
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

input[type=datetime-local], select {
    width: 220px;
    color: white;
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

#pass1 {
    background-color:rgba(115, 110, 110, 0);
    color:white;
    border: none;
    outline:none;
    width: 220px;
    height:40px;
    border: 2px solid #ccc;
}

#pass2 {
    background-color:rgba(115, 110, 110, 0);
    color:white;
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

$season_time_yesterday = $season_time.' -1 day';

$file = $_GET['file'];
$file1 = $_GET['file1'];
$name = $_GET['name'];
$name1 = $_GET['name1'];
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
  $unify = 1;
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

$handle = fopen("./".$usr."/DS18B20.txt", "r");
$DevicesNames_text = fread($handle, filesize("./".$usr."/DS18B20.txt")+1024);
fclose($handle);
$DevicesNames = explode("\n", $DevicesNames_text);

$files = scandir("./".$usr."/duomenys/");
$options = "";
$options1 = "";
$file1 = "";
$k = 0;
if(count($files) > 2)
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
      //pirmas select
      if(strlen($selected_file) >= 4)
      {
        if($selected_file === $files[$j])
        {
          $selected = "selected";
          $name = $Device[1];
        }
        else
        {
          $selected = "";
        }
      }
      else if(strlen($name) > 0)
      {
        if($name === $Device[1])
        {
          $selected = "selected";
        }
        else
        {
          $selected = "";
        }
      }
      //Antram selecte
      if(strlen($selected_file1) >= 4)
      {
        if($selected_file1 === $files[$j])
        {
          $selected1 = "selected";
          $name1 = $Device[1];
          $file1 = $Device[0];
        }
        else
        {
          $selected1 = "";
        }
      }
      else if(strlen($name1) > 0)
      {
        if($name1 === $Device[1])
        {
          $selected1 = "selected";
          $file1 = $Device[0];
        }
        else
        {
          $selected1 = "";
        }
      }
      
      $options = $options."<option value=\"".$files[$j]."\" ".$selected.">".$Device[1]."</option>";
      
      $options1 = $options1."<option value=\"".$files[$j]."\" ".$selected1.">".$Device[1]."</option>";
      $files_list[$k] = $files[$j];
      $k++;
    }
  }
  
  $options1 = "<option></option>".$options1;
  
if(strlen($selected_file) > 5)
{
  $file = $selected_file;
}
else
{
  if(strlen($file) < 4)
  {
    $file = $files_list[0];
    for($l = 0; $l < count($DevicesNames)-1; $l++)
      {
        $Device = explode(":", $DevicesNames[$l]);
        if($Device[0] === $file)
          break;
      }
    $name = $Device[1];
  }
}
$unify_checked = " ";
if($unify == 1)
  $unify_checked = "checked";
$today_checked = " ";
if($today == 1)
 $today_checked = "checked";
    
echo "<div id=\"WRAPPER\" align=center>
<table><form action=\"./grafikas.php\"> 
<tr>
<th><b><font color=white><nobr>Grafikas:</font><select name=\"selected_file\" id=pass2 style=\"background-color: #2D70B4\">".$options."</select></nobr></th>
<th><b><font color=white><nobr>Antras grafikas: </font><select name=\"selected_file1\" id=pass2 style=\"background-color: #2D70B4\">".$options1."</select></nobr></th>
<th><font color=white>
<nobr>Nuo: </font><input type=\"datetime-local\" value= \"".$start."\" name=\"start\" id=pass1 style=\"background-color: #2D70B4\"></nobr>
<font color=white><nobr>Iki: </font><input type=\"datetime-local\" value= \"".$end."\" name=\"end\" id=pass1 style=\"background-color: #2D70B4\"></nobr>
<input type=\"submit\" value=\"Rodyti\">
<nobr><input type=\"checkbox\" name=\"unify\" value=\"1\" ".$unify_checked."><font color=white>Suvienodinti y ašis</font></nobr>
<nobr><input type=\"checkbox\" name=\"today\" value=\"1\" ".$today_checked."><font color=white>Šiandien</font></nobr>
<input type=\"text\" value= \"".$file."\" name=\"file\" size=\"1\" style=\"visibility: hidden\">
<input type=\"text\" value= \"".$file1."\" name=\"file1\" size=\"1\" style=\"visibility: hidden\">
<input type=\"text\" value= \"".$name."\" name=\"name\" size=\"1\" style=\"visibility: hidden\">
<input type=\"text\" value= \"".$name1."\" name=\"name1\" size=\"1\" style=\"visibility: hidden\">
<input type=\"text\" value= \"1\" name=\"first\" size=\"1\" style=\"visibility: hidden\">
</form> 
</th><th><font color=white>".$time_now."</font>
</th></tr></table>
<iframe src=\"./graph1.php?name=".$name."&name1=".$name1."&file="."./".$usr."/duomenys/".$file."&file1="."./".$usr."/duomenys/".$file1."&start=".$start_int."&end=".$end_int."&unify=".$unify."\" height=\"800\" width=\"1100\" frameBorder=\"0\" scrolling=\"no\" align=\"middle\"></iframe>
</div>";
}
//<img src=\"./graph.php?name=".$name."&name1=".$name1."&file="."./".$usr."/duomenys/".$file."&file1="."./".$usr."/duomenys/".$file1."&start=".$start_int."&end=".$end_int."&unify=".$unify."\" alt=\"Temperatūra\">
else
{
  echo "<script>window.location = './index.php'</script>";
}
//echo "<iframe src=\"./graph1.php?name=".$name."&name1=".$name1."&file="."./".$usr."/duomenys/".$file."&file1="."./".$usr."/duomenys/".$file1."&start=".$start_int."&end=".$end_int."\" height=\"700\" width=\"1000\" frameBorder=\"0\" scrolling=\"no\" align=\"middle\"></iframe>";
//<nobr><input type=\"checkbox\" name=\"unify\" value=\"1\" ".$unify_checked."><font color=white>Suvienodinti y ašis</font></nobr>
?>
</body>
</html>