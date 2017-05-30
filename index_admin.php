<!DOCTYPE HTML>
<html>
<head>
<title>POWER Monitoring tool</title>
</head>
<body bgcolor="#E6E6FA">

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
echo "<center><h3 style=\"color:red\">VISUALLUX POWER CONSUMPION MONITORING TOOL</h3></center>";
?>


<fieldset>
<h4>UPS MANAGEMENT:</h4>

<form id="form1" method="post" action="insert_delete_devices.php">
<fieldset title="address">
<label for = "id"> ID:</label>
<input type="text" id="id" name="id" size=10>
<label for = "IP_address"> IP:</label>
<input type="text" id="IP_address" name="IP_address" size=10>

<label for = "PORT_address"> PORT:</label>
<input type="number" id="PORT_address" name="PORT_address" size=8 min="1" max="65535" step="1">

<label for = "community"> COMMUNITY:</label>
<input type="text" id="community" name="community" size=10>

<label for = "community"> DEVICES:</label>
<input type="text" id="devices" name="devices" size=20>

<label for = "community"> PDU:</label>
<input type="text" id="pdu" name="pdu" size=20>


<label>&nbsp</label>
<br><br>
<input type="submit" name="Query" value="Register" class="button">
<input type="submit" name="Delete_Device" value="Delete UPS" class="button">
<input type="submit" name="View_Device" value="View UPS" class="button">
<input type="reset" name="reset" value="reset">

</fieldset>
</form>
 <br>
<fieldset>
<h4>PDU MANAGEMENT:</h4>
<form id="form3" method="post" action="insert_delete_server.php">
<fieldset title="address">
<label for = "id"> ID:</label>
<input type="text" id="id" name="id" size=10>

<label for = "Server_IP_address"> IP:</label>
<input type="text" id="Server_IP_address" name="Server IP_address" size=10>

<label for = "Server_PORT_address"> PORT:</label>
<input type="number" id="Server_PORT_address" name="Server_PORT_address" size=8 min="1" max="65535" step="1">

<label for = "Server_COMMUNITY"> COMMUNITY:</label>
<input type="text" id="Server_COMMUNITY" name="Server_COMMUNITY" size=12>


<br><br>
<input type="submit" name="Add_Server" value="Add PDU" class="button">
<input type="submit" name="Delete_Server" value="Delete PDU" class="button">
<input type="submit" name="View_Server" value="View PDU" class="button">
<input type="reset" name="reset" value="reset">
<br><br>

</fieldset>
</form> 




<h4>USER REGISTRATION:</h4>
<form id="form3" method="post" action="user_registration.php">
<fieldset title="address">
<label for = "user_name"> EMAIL:</label>
<input type="text" id="user_name" name="user_name" size=10>

<label for = "pass_word"> PASSWORD:</label>
<input type="text" id="pass_word" name="pass_word" size=8>


<select name="previlage">
  <option value=""></option>
  <option value="admin">admin</option>
  <option value="user">user</option>
  <option value="guest">guest</option>
</select>



<br><br>
<input type="submit" name="register_user" value="Register User" class="button">
<input type="submit" name="delete_user" value="Delete User" class="button">
<input type="submit" name="view_user" value="View User" class="button">
<input type="reset" name="reset" value="reset">


</fieldset>
</form> 
<form>


<fieldset>
<h4>OID MANAGEMENT:</h4>
<label for = "oid_management"> OID Management:</label>

<a href="oid_reg_del_form.php">Oid Management</a>.<br> 
</fieldset>
<br>
</form>

<table cellspacing="5" cellpadding="8" border="2">
<tr>
<td>
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
</td>


<td>
<p>EMAIL AND THRESHOLD CONFIGURATION:</p>
<form id="form1" method="post" action="handler.php">
<fieldset title="address">
<label for = "id"> NAME:</label>
<input type="text" id="name" name="name" size=10>
<label for = "IP_address"> EMAIL:</label>
<input type="text" id="email" name="email" size=10>

<label for = "phone"> PHONE:</label> 
<input type="text" id="phone" name="phone" size=8>

<br>
<label for = "warning"> WARNING:</label>
<input type="number" id="warning" name="warning" size=1 min="1" max="100" step="1">

<label for = "danger"> DANGER:</label>
<input type="number" id="danger" name="danger" size=1 min="1" max="100" step="1">

<label for = "critical"> CRITICAL:</label>
<input type="number" id="critical" name="critical" size=1 min="1" max="100" step="1"><br>


<label>&nbsp</label>
<br><br>
<input type="submit" name="register" value="Register Handler" class="button">
<input type="submit" name="delete" value="Delete Handler" class="button">
<input type="submit" name="view" value="view" class="button">
<input type="reset" name="reset" value="reset">

</fieldset>
</form>
</td>
<td>

<p>TRAP MANAGER CONFIGURATION</p>
<form name="form1" action="configure_trap_manager.php" method="post">
<fieldset>
IP:  <input type="text" name="ipaddress" size=13>
Port:  <input type="number" name="port" min="1" max="65535" step="1" size=2>
Community:  <input type="text" name="community" size=10>
<br>
<label for = "warning"> WARNING:</label>
<input type="number" id="warning" name="warning" size=1 min="1" max="100" step="1">

<label for = "danger"> DANGER:</label>
<input type="number" id="danger" name="danger" size=1 min="1" max="100" step="1">

<label for = "critical"> CRITICAL:</label>
<input type="number" id="critical" name="critical" size=1 min="1" max="100" step="1"><br>
<br>
<input type="submit" name="form1_set" value="set" class="button">
<input type="submit" name="form1_view" value="view" class="button">
<input type="reset" value="cancel">
</fieldset>
</form>



</td>


</tr>
</table>
</body>
</html>
