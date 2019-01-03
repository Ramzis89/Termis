<?php
$fp = @fopen("esp1.txt", 'r'); 
if ($fp) {
   $DevicesData = explode("\n", fread($fp, filesize("esp1.txt")));
}
fclose($fp);

$fp = @fopen("DS18B20.txt", 'r'); 
if ($fp) {
   $Names = fread($fp, filesize("DS18B20.txt"));
}
fclose($fp);

$text = "<--------------->\\n";

for($i = 0; $i < count($DevicesData)-1; $i++)
{
  $DeviceInfo = explode("|", $DevicesData[$i]);
  $DeviceValue[$i] = $DeviceInfo[1];
  
  
  if(strpos($DeviceInfo[0], "RELAY") !== false){
   if($DeviceValue[$i] == "0")
     $DeviceValue[$i] = "Ijungta";
   else if($DeviceValue[$i] == "1")
     $DeviceValue[$i] = "Isjungta";
  }
  
  $Name = "";
  $pos1 = strpos($Names, $DeviceInfo[0]);
  if ($pos !== false) {
  
  $pos1 = strpos($Names, ":", $pos1+1);
  $pos2 = strpos($Names, ":", $pos1+1);
  
  $Name = substr($Names, $pos1+1, $pos2 - $pos1-1);
  
  $search  = array('ą', 'č', 'ę', 'ė', 'į', 'š', 'ų', 'ū', 'ž');
  $replace = array('a', 'c', 'e', 'e', 'i', 's', 'u', 'u', 'z');
  $Name = str_replace($search, $replace, $Name);
  }
  
  $text = $text.$Name."="."\\n".$DeviceValue[$i]."\\n\\n";
}

$text = $text."<--------------->\\n\\n\\n\\n";

$text = "{\"content\" : \"".$text."\",\"refresh_frequency\":1,\"vibrate\":0}";
echo $text;

        
?>