<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.container {
    position: relative;
    text-align: center;
    width:593px;
    font-family:verdana;
    font-style: italic;
}

.INF0 {
    position: absolute;
    top: 5px;
    left: 10px;
    color: black;
}


.T1 {
    position: absolute;
    top: 53px;
    left: 170px;
    color: red;
}

.T2 {
    position: absolute;
    top: 140px;
    left: 155px;
    color: blue;
}

.T3 {
    position: absolute;
    top: 122px;
    left: 338px;
    color: red;
    font-size:12px;
}

.T4 {
    position: absolute;
    top: 73px;
    left: 515px;
    color: red;
}

.T5 {
    position: absolute;
    top: 10px;
    left: 220px;
    color: red;
}


</style>
</head>
<body>
<?php

$handle = fopen("./esp1.txt", "r");
$Values_text = fread($handle, filesize("esp1.txt")+1024);
fclose($handle);

$Values = explode("\n", $Values_text);
$val1 = 0;
$val2 = 0;
$val3 = 0;
$val4 = 0;
$val5 = 0;
for($i = 0; $i < count($Values)-1; $i++)
{
  $Value = explode("|", $Values[$i]);
       if($Value[0] == "28EE0F6A1F16022F") $val1 = number_format($Value[1], 1)."C";//Peciaus iseinamas
  else if($Value[0] == "28FF78F6B316039F") $val2 = number_format($Value[1], 1)."C";//Peciaus ieinamas
  else if($Value[0] == "28FFF259B41603A0") $val3 = number_format($Value[1], 1)."C";//Boilerio
  else if($Value[0] == "28FFA7D3B3160582") $val4 = number_format($Value[1], 1)."C";//Grindu padavimas
  else if($Value[0] == "28FFB21AB4160321") $val5 = number_format($Value[1], 1)."C";//Radiatoriu ieinamas
}  



echo "
<div class=\"container\">
  <img src=\"./katiline1.png\" alt=\"Katilines schema\" style=\"width:578px;\">
  <div class=\"INF0\">".$Value[2]."</div>
  <div class=\"T1\">".$val1."</div>
  <div class=\"T2\">".$val2."</div>
  <div class=\"T3\">".$val3."</div>
  <div class=\"T4\">".$val4."</div>
  <div class=\"T5\">".$val5."</div>
</div>
";
?>



</body>
</html> 
