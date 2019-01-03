<?php

    include('var.php');
    $servername = $SERVER_NAME;
    $username = $SERVER_USER;
    $password = $SERVER_PASSWORD;
    $dbname = $SERVER_DBNAME;


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if (mysqli_connect_error()) {
    echo "Connect failed:".$mysqli->connect_error;
			//die('bad mojo');
		}


$adr = "28EE92811F1602A9";
$value = 71.0;
$date = 1236574;
$id = 1;

/*
//Patikrinam ar toks termometras yra duomenu bazeje
$sql = "SELECT * FROM Termometrai WHERE Address='".$adr."'";
$result = $conn->query($sql);

if($result->num_rows > 0)//Toks termometras jau yra
{
        $sql = "UPDATE Termometrai SET Date=".$date.",Value=".$value." WHERE Address='".$adr."';";
        
        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
}
else//Jei tokio nera
{
        $sql = "INSERT INTO Termometrai (ID, Address, Value, Date) VALUES (".$id.", '".$adr."', ".$value.", ".$date.");";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>".$conn->error;
        }
}*/
//---------------------------------------------------------------------------------------------------

$sql = "SELECT Address, Value, Date FROM Termometrai WHERE ID = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Address:" . $row["Address"]. ". Value:" . $row["Value"]. ". Date:"  . $row["Date"]." <br>";
    }
} else {
    echo "0 results";
}
//---------------------------------------------------------------------------------------------------


$conn->close();
?>
