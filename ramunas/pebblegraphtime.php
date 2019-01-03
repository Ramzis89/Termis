<?php

$time = $_GET['time'];

if(strlen($time) === 0)
    $time = htmlspecialchars($_POST['time']);
    
//$fp = @fopen("./namuoseGrafikasLaikas.txt", 'r');
//fclose($fp);

file_put_contents("./namuoseGrafikasLaikas.txt", $time);

echo $time;
?>