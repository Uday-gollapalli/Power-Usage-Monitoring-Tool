
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<body bgcolor="#90EE90">';

if (isset($_POST["form1_set"]) && !empty($_POST["form1_set"])){

// define variables and set to empty values
$ipaddressErr = $portErr = $communityErr = $thresholdErr = $warningErr = $dangerErr = $criticalErr ="";
$ipaddress = $port = $community = $warning = $danger = $critical = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["ipaddress"])) {
     $ipaddressErr = "Ipaddress is required";
echo $ipaddressErr;
exit;
   } else {
     $ipaddress = ($_POST["ipaddress"]);
   }
   
   if (empty($_POST["port"])) {
     $portErr = "Port is required";
echo $portErr;
exit;
   } else {
     $port = ($_POST["port"]);
   }
     
   if (empty($_POST["community"])) {
     $communityErr = "Community is required";
echo $communityErr;
exit;
   } else {
     $community = ($_POST["community"]);
   }

   if (empty($_POST["warning"])) {
     $warningErr = "Warning value is required";
echo $warningErr;
exit;
   } else {
     $warning = ($_POST["warning"]);
   }

   if (empty($_POST["danger"])) {
     $dangerErr = "Danger value is required";
echo $dangerErr;
exit;
   } else {
     $danger = ($_POST["danger"]);
   }

   if (empty($_POST["critical"])) {
     $criticalErr = "Critical value is required";
echo $criticalErr;
exit;
   } else {
     $critical = ($_POST["critical"]);
   }

}

// Get a connection for the database
//require_once('mysqli_connect.php');

//declaration of input file 
$login_file = "db.conf";
//echo $login_file;

//declaration of variables
$line = 0;

$handle = fopen("$login_file", "r");
if ($handle) {
//while loop for reading text lines and extract the
while (($string = fgets($handle)) !== false) {

$line++;

preg_match('/"([^"]+)"/', $string, $value);

//echo $value[1];

if ($line == 1){
$host = $value[1];
}
if ($line == 2){
$dpport = $value[1];
}
if ($line == 3){
$database = $value[1];
}
if ($line == 4){
$username = $value[1];
}
if ($line == 5){
$password = $value[1];
}
}
fclose($handle);
} 
else {
echo "error opening the log file.";
}

/*
echo $host;
echo $dbport;
echo $database;
echo $username;
echo $password;
*/

// $dbc will contain a resource link to the database
// @ keeps the error from showing in the browser

$dbc = @mysqli_connect($host, $username, $password, $database)
OR die('Could not connect to MySQL: ' .
mysqli_connect_error());



//check if table devices exit and if not create it

$querycheck="SELECT 1 FROM `TRAP_MANAGER`";

$query_result=$dbc->query($querycheck);

if ($query_result !== FALSE)
{

// Create a query for the database

$query = "SELECT id, IP, PORT, COMMUNITY, WARNING, DANGER, CRITICAL FROM TRAP_MANAGER WHERE id=1";

// Get a response from the database by sending the connection
// and the query
$response = @mysqli_query($dbc, $query);

// If the query executed properly proceed
if($response){

$rowcount=mysqli_num_rows($response);

if ($rowcount == 0){

$query = "INSERT INTO TRAP_MANAGER (id, IP, PORT, COMMUNITY, WARNING, DANGER, CRITICAL) VALUES (1, '$ipaddress', $port, '$community', $warning, $danger, $critical)";

if (mysqli_query($dbc, $query)) {
    echo "<h4>New trap manager configured successfully</h4>";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($dbc);
}

} 

else {


$query = "UPDATE TRAP_MANAGER SET IP = '$ipaddress', PORT = $port, COMMUNITY = '$community', WARNING = $warning, DANGER = $danger, CRITICAL = $critical WHERE id=1";

if (mysqli_query($dbc, $query)) {
    echo "<h4>New trap manager configured successfully</h4>";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($dbc);
}

}

} 

else {

echo "<h4>Couldn't issue database query</h4>";

echo mysqli_error($dbc);

}
}
else{


$query = "CREATE TABLE IF NOT EXISTS TRAP_MANAGER (
id int (11) NOT NULL AUTO_INCREMENT,
IP tinytext NOT NULL,
PORT int (11) NOT NULL,
COMMUNITY tinytext NOT NULL,
WARNING tinyint,
DANGER tinyint,
CRITICAL tinyint,
PRIMARY KEY ( id ) 
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

if (mysqli_query($dbc, $query)) {
    echo "";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($dbc);
}

$query = "INSERT INTO TRAP_MANAGER (id, IP, PORT, COMMUNITY, WARNING, DANGER, CRITICAL) VALUES (1, '$ipaddress', $port, '$community', $warning, $danger, $critical)";

if (mysqli_query($dbc, $query)) {
    echo "<h4>New trap manager configured successfully</h4>";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($dbc);
}

}
// Close connection to the database

mysqli_close($dbc);
}
else if (isset($_POST["form1_view"]) && !empty($_POST["form1_view"])){

// Get a connection for the database
//require_once('mysqli_connect.php');

//declaration of input file 
$login_file = "db.conf";
//echo $login_file;

//declaration of variables
$line = 0;

$handle = fopen("$login_file", "r");
if ($handle) {
//while loop for reading text lines and extract the
while (($string = fgets($handle)) !== false) {

$line++;

preg_match('/"([^"]+)"/', $string, $value);

//echo $value[1];

if ($line == 1){
$host = $value[1];
}
if ($line == 2){
$dpport = $value[1];
}
if ($line == 3){
$database = $value[1];
}
if ($line == 4){
$username = $value[1];
}
if ($line == 5){
$password = $value[1];
}
}
fclose($handle);
} 
else {
echo "error opening the log file.";
}

/*
echo $host;
echo $dbport;
echo $database;
echo $username;
echo $password;
*/

// $dbc will contain a resource link to the database
// @ keeps the error from showing in the browser

$dbc = @mysqli_connect($host, $username, $password, $database)
OR die('Could not connect to MySQL: ' .
mysqli_connect_error());

// Create a query for the database

$query = "SELECT id, IP, PORT, COMMUNITY, WARNING, DANGER, CRITICAL FROM TRAP_MANAGER";

// Get a response from the database by sending the connection
// and the query
$response = @mysqli_query($dbc, $query);

// If the query executed properly proceed
if($response){

echo '<table cellspacing="5" cellpadding="8" border="1">

<tr><td THRESHOLD><b>ID</b></td>
<td ><b>IP</b></td>
<td ><b>PORT</b></td>
<td ><b>COMMUNITY</b></td>
<td ><b>WARNING</b></td>
<td ><b>DANGER</b></td>
<td ><b>CRITICAL</b></td>';

// mysqli_fetch_array will return a row of data from the query
// until no further data is available
while($row = mysqli_fetch_array($response)){

echo '<tr><td >' . 
$row['id'] . '</td><td >' . 
$row['IP'] . '</td><td >' .
$row['PORT'] . '</td><td >' .
$row['COMMUNITY'] . '</td><td >' .
$row['WARNING'] . '</td><td >' . 
$row['DANGER'] . '</td><td >' .
$row['CRITICAL'] . '</td>';
echo '</tr>';
}

echo '</table>';

} else {

echo "Couldn't issue database query<br />";

echo mysqli_error($dbc);

}

// Close connection to the database
mysqli_close($dbc);


}

else{  
    echo "N0, mail is not set";
}

echo '</body>';

?>

