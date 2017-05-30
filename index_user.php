<!DOCTYPE HTML>
<html>
<head>
<title>Device and server Monitoring tool</title>
</head>
<body bgcolor="#E6E6FA">
<title> Device-Server monitoring</title>

<div style="float:right">
<form align="right" name="form1" method="post" action="index.php">
  <label class="logoutLblPos">
  <input name="submit2" type="submit" id="submit2" value="log out">
  </label>
</form>
</div>

<?php
ini_set('display_errors',1);
?>
<?php
require "config.php";
?>

<fieldset>
<legend>UPS PROFILE:</legend>
<h4>UPS REGISTRATION</h4>
<form id="form1" method="post" action="insert_delete_devices.php">
<fieldset title="address">
<label for = "IP_address"> IP:</label>
<input type="text" id="IP_address" name="IP_address" size=10>

<label for = "PORT_address"> PORT:</label>
<input type="text" id="PORT_address" name="PORT_address" size=8>


<label for = "community"> COMMUNITY:</label>
<input type="text" id="community" name="community" size=10>

<label for = "community"> DEVICES:</label>
<input type="text" id="devices" name="devices" size=20>


<label>&nbsp</label>
<br><br>
<input type="submit" name="Query" value="Register" class="button">
<input type="submit" name="View_Device" value="View Device" class="button">
<input type="reset" name="reset" value="reset">

</fieldset>
</form>
 <br>
<fieldset>
<legend>PDU PROFILE:</legend>
<h4>PDU REGISTRATION:</h4>
<form id="form3" method="post" action="insert_delete_server.php">
<fieldset title="address">
<label for = "Server_IP_address"> IP:</label>
<input type="text" id="Server_IP_address" name="Server IP_address" size=10>

<label for = "Server_PORT_address"> PORT:</label>
<input type="text" id="Server_PORT_address" name="Server_PORT_address" size=8>

<label for = "Server_COMMUNITY"> COMMUNITY:</label>
<input type="text" id="Server_COMMUNITY" name="Server_COMMUNITY" size=12>

<label for = "UPS"> UPS:</label>
<input type="text" id="ups" name="ups" size=12>

<br><br>
<input type="submit" name="Add_Server" value="Add Server" class="button">
<input type="submit" name="View_Server" value="View Server" class="button">
<input type="reset" name="reset" value="reset">


</fieldset>
</form> 

<p>RESTFUL API</p>
<form id="form3" method="post" action="rest_client.php">
<label for = "restful_api"> URL(http://...):</label>
<br>
<input type="text" id="rest" name="rest" size=50>
<br>
<input type="submit" name="Send_Request" value="Send Request" class="button">
</form> 

<br>
<form id="form4" method="post" action="rest_client_graph.php">
<label for = "rest_data">Restful Data:</label>
<br>
<input type="text" id="rest_data" name="rest_data" size=50>
<br>
<input type="submit" name="Display_graph" value="Display Graph" class="button">
</form> 
<br>


</body>
</html>
