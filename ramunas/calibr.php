<?php

$img = "./katiline.png";
$newImage = imagecreatefrompng($img);
$txtColor = imagecolorallocate($newImage, 255, 0, 0);
imagestring($newImage, 5, 200, 33, "29.1C", $txtColor);//Peciaus iseinamas
imagestring($newImage, 5, 530, 93, "24.8C", $txtColor);//Radiatoriu ieinamas
imagestring($newImage, 5, 415, 33, "19.3C", $txtColor);//Boilerio ieinamas
$txtColor = imagecolorallocate($newImage, 0, 0, 255);
imagestring($newImage, 5, 175, 275, "16.8C", $txtColor);//Peciaus ieinamas
imagestring($newImage, 5, 320, 280, "18.5C", $txtColor);//Radiatoriu gristamas
imagejpeg($newImage, "./out.jpg", 100);

?>
<div style="width:593px;height:360px;background:url(./out.jpg)" id="sc-main">
</div>