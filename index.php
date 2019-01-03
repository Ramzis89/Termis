<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>
<style>
{ margin: 0; padding: 0; }

    html { 
        height:100%;
        background: url('\\background.jpg') no-repeat center center fixed; 
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
body {
    background-color: #9850;
}
#pass {
    background-color:rgba(115, 110, 110, 0);
    color:white;
    border: none;
    outline:none;
    height:45px;
    border: 3px solid #ccc;
    transition:height 1s;
    -webkit-transition:height 1s;
}
#pass:focus {
    height:50px;
    font-size:16px;
}
input[type=password], select {
    width: 150px;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 3px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
input[type=text], select {
    width: 150px;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 3px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
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

</style>
<?php
//
//session_unset(); 
//print_r($_SESSION);

    include('var.php');
    $servername = $SERVER_NAME;
    $username = $SERVER_USER;
    $password = $SERVER_PASSWORD;
    $dbname = $SERVER_DBNAME;
    
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
$savedpsw = "";
$psw = htmlspecialchars($_POST['psw']);//$_GET['psw'];
$psw1 = htmlspecialchars($_POST['psw1']);//$_GET['psw1'];
$usr = htmlspecialchars($_POST['usr']);//$_GET['usr'];
$usr1 = $usr;
$usr = strtolower($usr);

$date = new DateTime("now");
$date->setTimezone(new DateTimeZone('Europe/Vilnius'));
$season_time = $date->format('P')." hour";
$season_time = substr($season_time, 0, 3)." hour";
 
//$season_time = "+3 hour";

if($psw1 == "NoneOrBad")
  echo "<h1><font color=white>Neteisingas slaptažodis!</font></h1>";

if((strlen($psw) > 3) &&(strlen($usr) > 3))
{
  if(file_exists("./".$usr))
  {
  
    $fp = @fopen("./".$usr."/psw.txt", 'r');
    $savedpsw = @fread($fp, filesize("./".$usr."/psw.txt")+1);
    fclose($fp);
    $savedpsw = psw_crypt($savedpsw, 'd');

    if($psw == $savedpsw)
    {
      $_SESSION["security"] = "yes";
      $_SESSION["usr"] = $usr;
      
      file_put_contents("./log.txt", $usr." ".$date->format('Y-m-d H:i:s').PHP_EOL, FILE_APPEND | LOCK_EX);
      
      if($usr === "admin")
        echo "<script>window.location = './".$usr."/admin.php'</script>";
      else
        echo "<script>window.location = './term.php'</script>";
    }
    else
    {
      if(strlen($psw) >= 0)
        echo "<script>window.location = './index.php?usr=".$usr1."&psw1=NoneOrBad'</script>";
        
    }
  }
  else
  {
    echo "<h1><font color=white>Nėra tokio vartotojo!</font></h1>";
  }
}
else
{
  if($usr !== "")
    if(strlen($usr) <= 3)
      echo "<h1><font color=white>Blogai įvestas vartotojas</font></h1>";
}


if($_SESSION["security"] !== "yes")
{
  // remove all session variables
  session_unset(); 

  // destroy the session 
  session_destroy(); 

echo "<br>
<div align=\"center\">
<img src=\"thermometer.png\"/>
</div>
<br><br><br>


<div id=\"WRAPPER\" align=center>
      
    <div class=\"term\">
    
        <form name=\"form1\" action=\"index.php\" method=\"post\">
            <font color=white><b>Vartotojas:<br>
            <input name=\"usr\" type=\"text\" id=pass value=\"".$usr1."\">
            <br>Slaptažodis:<br></b></font>
            <input name=\"psw\" type=\"password\" id=pass><br>
            <input type=\"submit\" value=\"Prisijungti\">
        </form>
    </div>
</div>";
//<input type=\"submit\" value=\"Prisijungti\">

  //echo "<br><br><br><br><br><br><br><br><br>
  //<table align=\"center\">
  //<tr><th colspan=2><h1 style=\"color:white;\">Prisijunkite</h1> </th>
  //<tr>  <form action=\"./login.php\">
  //<th><input type=\"password\" name=\"psw\" ></th>
  //<th><input type=\"submit\" value=\"Prisijungti\"></th></tr></table>
  //</form>";
//<input type=\"image\" src=\"background.jpg\" alt=\"Prisijungti\" name=\"submit\" value=\"submit\">
}
else
{
  echo "<script>window.location = './term.php'</script>";
}


?>

</body>
</html>
