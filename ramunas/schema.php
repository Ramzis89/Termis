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
    top: 20px;
    left: 10px;
    color: black;
}

.INF1 {
    position: absolute;
    top: 40px;
    left: 10px;
    color: black;
}

.INF2 {
    position: absolute;
    top: 60px;
    left: 10px;
    color: black;
}

.INF3 {
    position: absolute;
    top: 80px;
    left: 10px;
    color: black;
}

.INF4 {
    position: absolute;
    top: 100px;
    left: 10px;
    color: black;
}
.INF5 {
    position: absolute;
    top: 150px;
    left: 627px;
    color: black;
}
.T1 {
    position: absolute;
    top: 180px;
    left: 162px;
    color: red;
}

.T2 {
    position: absolute;
    top: 275px;
    left: 175px;
    color: blue;
}
.T3 {
    position: absolute;
    top: 135px;
    left: 110px;
    color: red;
}

.T5 {
    position: absolute;
    top: 55px;
    left: 540px;
    color: red;
}

.T6 {
    position: absolute;
    top: 280px;
    left: 400px;
    color:blue;
}
.T7 {
    position: absolute;
    top: 182px;
    left: 530px;
    color: red;
}
.T8 {
    position: absolute;
    top: 3px;
    left:590px;
    color: red;
}
.T9 {
    position: absolute;
    top: 230px;
    left:612px;
    color: red;
}
.T10 {
    position: absolute;
    top: 278px;
    left:698px;
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
$laikas = 0;
$val1 = 0;
$val2 = 0;
$val3 = 0;
$val4 = 0;
$val5 = 0;
$val6 = 0;
$val7 = 0;
$val8 = 0;
$val9 = 0;
$val10 = 0;
$val11 = 0;
$val12 = 0;
$val13 = 0;
$val14 = 0;

for($i = 0; $i < count($Values)-1; $i++)
{
  $Value = explode("|", $Values[$i]);
       if($Value[0] == "28EE92811F1602A9" && $val1 == 0) $val1 = number_format($Value[1], 1)."C";//Pečiaus išeinamas
  else if($Value[0] == "28FF1742C01604F6" && $val2 == 0) $val2 = number_format($Value[1], 1)."C";//Pečiaus grįžtamas
  else if($Value[0] == "cold-C7268D6A" && $val3 == 0) $val3 = number_format($Value[1], 1)."C";//Kaminas
  else if($Value[0] == "28FFDF61C21603DE" && $val4 == 0) $val4 = "Lauke: ".number_format($Value[1], 1)." C";//Lauke
  else if($Value[0] == "28FF855FC21603AB" && $val5 == 0) $val5 = number_format($Value[1], 1)."C";//Boileris
  else if($Value[0] == "28FFC67BC2160372" && $val6 == 0) $val6 = number_format($Value[1], 1)."C";//Grindinio griztamas
  else if($Value[0] == "28EEDDF822160138" && $val7 == 0) $val7 = number_format($Value[1], 1)."C";//Radiatorius
  else if($Value[0] == "28EE1EF31F16010E" && $val8 == 0) $val8 = "Namie: ".number_format($Value[1], 1)."C";//Namie
  else if($Value[0] == "SLEGIS" && $val9 == 0) $val9 = "Slegis: ".number_format($Value[1], 1)."hPa";//Slegis
  else if($Value[0] == "28FFC67FC216037C" && $val10 == 0) $val10 = "Katilineje: ".number_format($Value[1], 1)."C";//Katilineje
  else if($Value[0] == "28FF593763170442" && $val11 == 0) $val11 = number_format($Value[1], 1)."C";//Kolektorius
  else if($Value[0] == "28ff86c961170458" && $val12 == 0) $val12 = "Oras: ".number_format($Value[1], 1)."C";//Šiltnamio oras
  else if($Value[0] == "28ff77f862170410" && $val13 == 0) $val13 = "Žemė: ".number_format($Value[1], 1)."C";//Šiltnamio žemė
  else if($Value[0] == "OutputPWM" && $val14 == 0) $val14 = number_format($Value[1]/1024*100, 0)."%";//PWM išėjimas
  if($laikas == 0) $laikas = $Value[2];
}  



echo "
<div class=\"container\">
  <img src=\"./katiline21.png\" alt=\"Katilines schema\" style=\"width:795px;\">
  <div class=\"INF0\">".$laikas."</div>
  <div class=\"T1\">".$val1."</div>
  <div class=\"T2\">".$val2."</div>
  <div class=\"T3\">".$val3."</div>
  <div class=\"INF1\">".$val4."</div>
  <div class=\"T5\">".$val5."</div>
  <div class=\"T6\">".$val6."</div>
  <div class=\"T7\">".$val7."</div>
  <div class=\"INF2\">".$val8."</div>
  <div class=\"INF3\">".$val9."</div>
  <div class=\"INF4\">".$val10."</div>
  <div class=\"T8\">".$val11."</div>
  <div class=\"T9\">".$val12."</div>
  <div class=\"T10\">".$val13."</div>
  <div class=\"INF5\">".$val14."</div>
</div>
";
?>



</body>
</html> 
