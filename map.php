<?php

if(isset($_POST['view_map']))
{

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
<table width="1200" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>id</th>
<th>IP</th>
<th>PORT</th>


<?php
$order = "SELECT * FROM UPS";
$result = mysql_query($order);	//order executes
while($row = mysql_fetch_assoc($result)) {
          
//echo "id: " .$row["id"]. "-IP: " . $row["IP"] . " PORT: ". $row["PORT"]. "COMMUNITY:". $row["COMMUNITY"]. "INTERFACES: " . $row["INTERFACES"]. "<br>";
echo "<tr>"; 

echo "<td>". $row['PDU_IP']."</td>";
echo "<td>". $row["PDU_ID"]."</td>";
echo "<td>". $row["DEVICES"]."</td>";

echo "</tr>";
}
}
?>
