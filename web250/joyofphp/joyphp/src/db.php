 <?php
$mysqli = new mysqli('mySQL', 'if0_40161635', 'cRFyJqBZX4pM', 'Cars' );
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
//select a database to work with
$mysqli->select_db("Cars");
 
?>