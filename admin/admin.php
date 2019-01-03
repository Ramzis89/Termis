<?php
session_start();
$page = $_SERVER['PHP_SELF'];
$sec = "60";
?>

<html>
<head>
<meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
<style>

{ margin: 0; padding: 0; }

    html { 
        background: url('../background.jpg') no-repeat center center fixed; 
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
    background-color: #808080;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

#pass1 {
    background-color:rgba(115, 110, 110, 0);
    color:white;
    border: none;
    outline:none;
    width: 150px;
    height:30px;
    border: 3px solid #ccc;
    transition:height 1s;
    -webkit-transition:height 1s;
    -moz-border-radius: 7px;
    border-radius: 7px;
}
#pass1:focus {
    height:50px;
    font-size:16px;
}



table {
    border-collapse: collapse;
    width: 20%;
}

th, td {
    text-align: left;
    padding: 6px;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #4CAF50;
    color: white;
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

</style>
</head>
<body>

<?php
if($_SESSION["security"] === "yes")
{
include('var.php');


function psw_crypt( $string, $action = 'e' ) {
    include('var.php');
    $secret_key = $SECURITY_KEY;
    $secret_iv = $SECURITY_IV;
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}

function delTree($dir) { 
   $files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
      (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
    } 
    return rmdir($dir); 
  } 

echo "
<ul>
  <li><a href=\"./admin.php\">Vartotojai</a></li>
  <li><a href=\"../logout.php\">Atsijungti</a></li>
</ul><br>
";

$usr = $_SESSION["usr"];
$vartotojas = $_GET['vartotojas'];
$slaptazodis = $_GET['slaptazodis'];
$istrinti = $_GET['istrinti'];
$adminpass = $_GET['adminpass'];
$username = $_GET['username'];
$userpass = $_GET['userpass'];
$connect = $_GET['connect'];

$date = new DateTime("now");
$date->setTimezone(new DateTimeZone('Europe/Vilnius'));
$season_time = $date->format('P')." hour";
$season_time = substr($season_time, 0, 3)." hour";

//-------------------------------------------
if(strlen($vartotojas) > 3)
{
  mkdir("../".$vartotojas);
  mkdir("../".$vartotojas."/duomenys");
  copy("./esp1.php", "../".$vartotojas."/esp1.php");
  touch("../".$vartotojas."/psw.txt");
  file_put_contents("../".$vartotojas."/psw.txt", psw_crypt("admin", 'e'));
  touch("../".$vartotojas."/customformat.txt.txt");
  echo "<h1><font color=white><b>Vartotojas sukurtas!</b></font></h1>";
}
//------------------------------------------------
if(strlen($istrinti) > 4)
{
  if(delTree("../".$istrinti) === true)
  echo "<h1><font color=white><b>Vartotojas ištrintas!</b></font></h1>";
}
//-------------------------------------------------
if(strlen($adminpass) > 4)
{
  file_put_contents("./psw.txt", psw_crypt($adminpass, 'e'));
  echo "<h1><font color=white><b>Slaptažodis pakeistas!</b></font></h1>";
}
//-------------------------------------------------
if(strlen($username) > 4)
{
        if(strlen($userpass) > 4)
        {
          file_put_contents("../".$username."/psw.txt", psw_crypt($userpass, 'e'));
          echo "<h1><font color=white><b>Slaptažodis pakeistas!</b></font></h1>";
        }
        else
        {
          echo "<h1><font color=white><b>Slaptažodis per trumaps!</b></font></h1>";
        }
}
if(strlen($connect) > 3)
{
      $_SESSION["security"] = "yes";
      $_SESSION["usr"] = $connect;
      echo "<script>window.location = '../term.php'</script>";
}
//----------------------------------------------------
$files = scandir("../");
$j = 0;
for($i=0; $i < count($files); $i++)
{
  if((is_dir("../".$files[$i])) && ($files[$i][0] !== ".") && ($files[$i] !== "jpgraph") && ($files[$i] !== "jqplot") && ($files[$i] !== "admin"))
    $folders[$j++] = $files[$i];
}


  echo "
  <div id=\"WRAPPER\" align=left>
<table>
  <tr bgcolor=\"#FF0000\">
          <th> Vartotojai: </th>
  </tr>";
  for($i=0; $i < count($folders); $i++)
  {
    echo "<tr><th>".$folders[$i]."</th></tr>";
  }

 echo" </table><br></div>
 <form action=\"./admin.php\">
 <br><b><font color=white>Vartotojas:<input type=\"text\" name=\"vartotojas\" id=pass1>
 <br>Slaptažodis:</font></b><input type=\"text\" name=\"slaptazodis\" id=pass1>
 <br><input type=\"submit\" value=\"Prideti naują vartotoją\">
 </form>
 
 <form action=\"./admin.php\">
 <br><b><font color=white>Prisijungti prie vartotojo:</font></b>
 <select name=connect id=pass2 style=\"background-color: #2D70B4\"><option></option>";
 for($i=0; $i < count($folders); $i++)
 {
    echo "<option value=".$folders[$i].">".$folders[$i]."</option>";
  }
 echo "</select>
 <br><input type=\"submit\" value=\"Prisijungti\">
 </form>
 
 <form action=\"./admin.php\">
 <br><b><font color=white>Ištrinti:</font></b>
 <select name=istrinti id=pass2 style=\"background-color: #2D70B4\"><option></option>";
 for($i=0; $i < count($folders); $i++)
 {
    echo "<option value=".$folders[$i].">".$folders[$i]."</option>";
  }
 echo "</select>
 <br><input type=\"submit\" value=\"Ištrinti vartotoją\">
 </form>
 
 <form action=\"./admin.php\">
 <br><b><font color=white>Pakeisti user slaptažodį:</font></b>
 <select name=username id=pass2 style=\"background-color: #2D70B4\"><option></option>";
 for($i=0; $i < count($folders); $i++)
 {
    echo "<option value=".$folders[$i].">".$folders[$i]."</option>";
  }
 echo "</select>
 <br><input type=\"text\" name=\"userpass\" id=pass1>
 <br><input type=\"submit\" value=\"Pakeisti\">
 </form>
 
 
 <form action=\"./admin.php\">
 <br><b><font color=white>Pakeisti admin slaptažodį:</font></b>
 <br><input type=\"text\" name=\"adminpass\" id=pass1>
 <br><input type=\"submit\" value=\"Pakeisti\">
 </form>
 ";





}
?>



</body>
</html>
