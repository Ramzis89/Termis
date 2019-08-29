<?php
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------

    include('var.php');
    $servername = $SERVER_NAME;
    $username = $SERVER_USER;
    $password = $SERVER_PASSWORD;
    $dbname = $SERVER_DBNAME;

    $UserID = 1;
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        //die("Connection failed: " . $conn->connect_error);
    } 
$usr = "ramunas";

//-----------------------Nuskaitom is duombazes------------------------------------------------
$sql = "SELECT ID, Name FROM Users WHERE Name = '".$usr."'";
$result = $conn->query($sql);

if ($result->num_rows > 0)
{
  $row = $result->fetch_assoc();
  //echo $row["ID"]."-".$row["Name"];
  $sql = "SELECT Address, Value, Date FROM Termometrai WHERE ID = ".$row["ID"];
  $result = $conn->query($sql);

  if ($result->num_rows > 0)
  {
     $num = 0;
     while($row = $result->fetch_assoc())
     {
        $devices[$num] = $row["Address"]."|".$row["Value"]."|".date('Y-m-d H:i:s', $row["Date"]);
        $num++;
     }
  }
}
$conn->close();
//---------------------------------------------------------------------------------------------

 while ($i < count($devices)-1)
 {
    $line = explode("|", $devices[$i]);
    $i++;
    
  if($line[0] == "28FF855FC21603AB")//Boileris
  {  
	  $Bl = $line[1]; 
	  break;
  } 
 }
 echo $line[0]."|".$line[1]."|".$line[2]."\r";

?>
