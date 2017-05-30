<?php
$file_handle = fopen("db.conf", "rb");
$credentials = array();

while (!feof($file_handle) ) {

$line_of_text = fgets($file_handle);
$parts = explode('"', $line_of_text);
#print_r ($parts);
array_push($credentials,"$parts[1]");
}
#print_r ($credentials);
fclose($file_handle);
$database = "$credentials[2]";
$conn = mysql_connect($credentials[0], $credentials[3], $credentials[4]) or die('Could Not Connect');



if (isset($_POST['username']) || isset($_POST['password']))
{
$USERNAME = $_POST['username'];
$PASSWORD_text = $_POST['password'];

$salt = 'db4968a3db5f6ed2f60073c747bb4fb5';

$hash = md5($salt . $PASSWORD_text); // Value: db4968a3db5f6ed2f60073c747bb4fb5

$PASSWORD = $hash;


if (!empty($USERNAME) && !empty($PASSWORD)) 
{
mysql_select_db("$database",$conn);
$query = "SELECT id,TYPE FROM USER WHERE USER.USERNAME = '$USERNAME' AND USER.PASSWORD='$PASSWORD'";
$query_run = mysql_query($query);
$row = mysql_fetch_assoc($query_run);

$id = $row['id'];
$TYPE = $row['TYPE'];

if ($id)
{
if($TYPE == "admin")
{

header("Location: index_admin.php");
exit();
}elseif($TYPE == "user")
{
header("Location: index_user.php");
exit();
}elseif($TYPE == "guest")
{
header("Location: index_guest.php");
exit();
}else
{
echo "Your Administrative Privilage is not properly inserted to the database, Please contact the administror!";
exit();
}
}else
{
echo "<h3 style=\"color:red\">Wrong UserName and/or PassWord!</h3>";
}
}else
{
echo "Please fill both the credentials together!\n";
}
}


?>
