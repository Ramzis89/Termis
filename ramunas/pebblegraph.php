<?php

$graf = $_GET['graph'];

if(strlen($graf) < 2)
    $graf = htmlspecialchars($_POST['graf']);
    
$fp = @fopen("./namuoseGrafikas.txt", 'r');
$grafikas = fgets($fp);
fclose($fp);

if(strlen($graf) < 5)
{
  $graf = $grafikas;
}
else
{
  file_put_contents("./namuoseGrafikas.txt", $graf);
  $grafikas = $graf;
}
echo "<form action=\"./pebblegraph.php\" method=\"post\">
  Grafikas: ";
  $fp = @fopen("./DS18B20.txt", 'r'); 
if ($fp) {
        if(filesize("./DS18B20.txt") > 0)
           $devices = explode("\n", fread($fp, filesize("./DS18B20.txt")));
}
fclose($fp);

$fp = @fopen("../vardenis/DS18B20.txt", 'r'); 
if ($fp) {
        if(filesize("../vardenis/DS18B20.txt") > 0)
           $devices1 = explode("\n", fread($fp, filesize("../vardenis/DS18B20.txt")));
}
fclose($fp);

$devices = array_merge($devices, $devices1);

echo "<select name=\"graf\">";
$files = scandir("./duomenys/");
for($i = 0; $i < count($files); $i++)
{
  if(strlen($files[$i]) > 4)
  {
    for($j = 0; $j < count($devices)-1; $j++)
    {
      $device = explode(':', $devices[$j]);
      if($files[$i] == $device[0])
      {
        if($grafikas == "./duomenys/".$files[$i])
          $selected = " selected";
        else
          $selected = "";
        echo "<option value=\"./duomenys/".$files[$i]."\" ".$selected.">".$device[1]."</option>";
        break;
      }
    }
  }
}
$files = scandir("../vardenis/duomenys/");
for($i = 0; $i < count($files); $i++)
{
  if(strlen($files[$i]) > 4)
  {
    for($j = 0; $j < count($devices)-1; $j++)
    {
      $device = explode(':', $devices[$j]);
      if($files[$i] == $device[0])
      {
      if($grafikas == "../vardenis/duomenys/".$files[$i])
          $selected = " selected";
        else
          $selected = "";
        echo "<option value=\"../vardenis/duomenys/".$files[$i]."\" ".$selected.">".$device[1]."</option>";
        break;
      }
    }
  }
}
echo "</select>
<input type=\"submit\" value=\"Submit\">
</form>";


?>

