<?php
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

<h4>PDU OID REGISTRATION:</h4>
<form id="form" method="post" action="oid_reg_del.php">
<fieldset title="address">
<label for = "power_watt"> POWER_WATT:</label>
<input type="text" id="power_watt" name="power_watt" size=30>

<label for = "power_va"> POWER_VA:</label>
<input type="text" id="power_va" name="power_va" size=30>

<label for = "pdu_load"> PDU_LOAD:</label>
<input type="text" id="pdu_load" name="pdu_load" size=30>

<label for = "UPS"> COMPANY:</label>
<input type="text" id="company_pdu" name="company_pdu" size=10>

<br><br>
<input type="submit" name="Add_Oid_PDU" value="Add OID" class="button">
<input type="submit" name="Delete_Oid_PDU" value="Delete OID" class="button">
<input type="submit" name="View_Oid_PDU" value="View OID" class="button">
<input type="reset" name="reset" value="reset">
<br><br>

</fieldset>
</form> 




<h4>UPS OID REGISTRATION:</h4>
<form id="form" method="post" action="oid_reg_del.php">
<fieldset title="address">
<label for = "current"> CURRENT:</label>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
<input type="text" id="current_ups" name="current_ups" size=30>
<br>
<label for = "voltage"> VOLTAGE:</label>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
<input type="text" id="voltage_ups" name="voltage_ups" size=30>
<br>
<label for = "curent_ups_load"> CURRENT UPS LOAD:</label>&nbsp &nbsp &nbsp 
<input type="text" id="current_ups_load" name="current_ups_load" size=30>
<br>
<label for = "UPS"> REM.BATT.CAPACITY:</label>  &nbsp &nbsp 
<input type="text" id="rem_batt_capacity" name="rem_batt_capacity" size=30>
<br>
<label for = "UPS"> UPS BATT.RUNTIME:</label> &nbsp &nbsp &nbsp 
<input type="text" id="ups_batt_runtime" name="ups_batt_runtime" size=30>
<br>
<label for = "UPS"> COMPANY:</label> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
<input type="text" id="company_ups" name="company_ups" size=10>

<br><br>
<input type="submit" name="Add_Oid_UPS" value="Add OID" class="button">
<input type="submit" name="Delete_Oid_UPS" value="Delete OID" class="button">
<input type="submit" name="View_Oid_UPS" value="View OID" class="button">
<input type="reset" name="reset" value="reset">
<br><br>

</fieldset>
</form> 


</body>
</html>


