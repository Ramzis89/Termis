<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
} else {
    if(($_SERVER['PHP_AUTH_PW'] == "ramzis891") && ($_SERVER['PHP_AUTH_USER'] == "admin"))
    {
$dir    = '/home/pi/motion/images/';
$files1 = scandir($dir);

$image = $_GET["img"];

echo "<head>
</head>
<body>
<table><tr>
		<td><a href=\"download.php?img=".$image."\">Download ".number_format((filesize($dir.$files1[$image])/1024/1024), 2)." MB</td></a>
		<td>&#8195;&#8195;</td>
		<td><a href=\"delete.php?img=".$files1[$image]."\">Delete record</td></a></tr></table>
<iframe src=\"http://".$_SERVER['HTTP_HOST']."/show.php?img=".$image."\" height=\"720\" width=\"1280\"></iframe>
</body>";
}
}
?>
