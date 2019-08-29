<?php

$dir    = '/home/pi/motion/images/';
$files1 = scandir($dir);

$image = $_GET["img"];
if (file_exists($dir.$files1[$image])) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($files1[$image]).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($dir.$files1[$image]));
    readfile($dir.$files1[$image]);
    exit;
}
?>
