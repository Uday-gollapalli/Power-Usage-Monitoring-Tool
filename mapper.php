<?php
ini_set('display_errors',1);
include('config.php');
 

$id = $_GET['id'];
?>
<html>
<head>
<title> Device information</title>
</head>

<body bgcolor="#90EE90">

<div style="float:right">
<form align="right" name="form1" method="post" action="index.php">
  <label class="logoutLblPos">
  <input name="submit2" type="submit" id="submit2" value="log out">
  </label>
</form>
</div>

<form id ="form" method="post" action="select_devices.php">
<table width="600" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>PDU ID</th>
<th>UPS ID</th>
<th>DEVICES</th>


<?php
$order = "SELECT * FROM PDU_UPS";
$result = mysql_query($order);	//order executes
while($row = mysql_fetch_assoc($result)) 
{
          

echo "<tr>"; 
echo "<td>". $row['PDU_ID']."</td>";
echo "<td>". $row["UPS_ID"]."</td>";
echo "<td>". $row["DEVICES"]."</td>";

echo "</tr>";
}

?>

