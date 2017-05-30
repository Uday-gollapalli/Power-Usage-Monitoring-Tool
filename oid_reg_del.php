<?php


//the example of inserting data with variable from HTML form
#mysql_connect("localhost","root","root");//database connection
#mysql_select_db("addishiwot");
include('config.php');
// Get values from form 


$power_watt = $_POST['power_watt'];
$power_va= $_POST['power_va'];
$pdu_load=$_POST['pdu_load'];
$company_pdu=$_POST['company_pdu'];


$current_ups= $_POST['current_ups'];
$voltage_ups = $_POST['voltage_ups'];
$current_ups_load=$_POST['current_ups_load'];
$rem_batt_capacity=$_POST['rem_batt_capacity'];
$ups_batt_runtime = $_POST['ups_batt_runtime'];
$company_ups = $_POST['company_ups'];

ini_set('display_errors',1);



if(isset($_POST['Delete_Oid_PDU']))
{
//var_dump($_POST);
if($company_pdu=="")
{
echo("\nitems can't be empty");
}
else
{

$order = "SELECT * FROM COMPANY_PDU WHERE COMPANY='$company_pdu'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
                  if(mysql_num_rows($result) <=0)
                     {echo("PDU doesn't exist");}
else
{

$order = "DELETE FROM COMPANY_PDU WHERE COMPANY='$company_pdu'";
$result = mysql_query($order);	//order executes
if($result)
{
 echo("PDU OID from company $company_pdu Deleted");
}
else
{
 echo("Unable to Delete PDU OID");
}
}
}
}
else if(isset($_POST['Add_Oid_PDU']))
{
//var_dump($_POST)
if($company_pdu=="")
{
echo("\nitems can't be empty");
}
else
{
$order = "SELECT * FROM COMPANY_PDU WHERE COMPANY='$company_pdu'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
                  if(mysql_num_rows($result) >0)
                     {echo("OID from company $company_pdu already exists");}
  else
  {
//inserting data order
$order = "INSERT INTO COMPANY_PDU(POWER_WATT,POWER_VA,PDU_LOAD ,COMPANY) VALUES('$power_watt','$power_va','$pdu_load','$company_pdu')";

//declare in the order variable
$result = mysql_query($order);	//order executes
   if($result)
   { echo("OID Registration succeeded"); }
   else
     { echo("OID Registration failed");}
  }
}
}


else if(isset($_POST['View_Oid_PDU']))
{


if($company_pdu=="")
{

$order = "SELECT * FROM COMPANY_PDU";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
                  if(mysql_num_rows($result) <=0)
                     {echo("OID table is empty"); exit;}

?>
<html>
<head>
<title> Server information</title>
</head>

<body bgcolor="#90EE90">
<table width="600" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>id</th>
<th>POWER(WATT)</th>
<th>POWER(VA)</th>
<th>PDU LOAD</th>
<th>COMPANY</th>


<?php
$order = "SELECT * FROM COMPANY_PDU";
$result = mysql_query($order);	//order executes
while($row = mysql_fetch_assoc($result)) {
          

echo "<tr>"; 

echo "<td>". $row['id']."</td>";
echo "<td>". $row["POWER_WATT"]."</td>";
echo "<td>". $row["POWER_VA"]."</td>";
echo "<td>". $row["PDU_LOAD"]."</td>";
echo "<td>". $row["COMPANY"]."</td>";
echo "</tr>";
                }
?>

</table>
<br>

</body>
</html>

<?php
exit;
}


//$result = $connect->query($order);

$order = "SELECT * FROM COMPANY_PDU WHERE COMPANY='$company_pdu'";
$result = mysql_query($order);	//order executes


                  if(mysql_num_rows($result) <=0)
                     {echo("OID form company $company_pdu doesn't exist"); exit;}
?>
<html>
<head>
<title> Server information</title>
</head>

<body bgcolor="#90EE90">
<table width="600" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>id</th>
<th>POWER(WATT)</th>
<th>POWER(VA)</th>
<th>PDU LOAD</th>
<th>COMPANY</th>

<?php
$order = "SELECT * FROM COMPANY_PDU WHERE COMPANY='$company_pdu'";
$result = mysql_query($order);	//order executes

while($row = mysql_fetch_assoc($result)) {
          

echo "<tr>"; 

echo "<td>". $row['id']."</td>";
echo "<td>". $row["POWER_WATT"]."</td>";
echo "<td>". $row["POWER_VA"]."</td>";
echo "<td>". $row["PDU_LOAD"]."</td>";
echo "<td>". $row["COMPANY"]."</td>";
echo "</tr>";
                }
?>
</table>
<br>
</body>
</html>

<?php
exit;
}



if(isset($_POST['Delete_Oid_UPS']))
{
//var_dump($_POST);
if($company_ups=="")
{
echo("\nitems can't be empty");
}
else
{

$order = "SELECT * FROM COMPANY_UPS WHERE COMPANY='$company_ups'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
                  if(mysql_num_rows($result) <=0)
                     {echo("UPS doesn't exist");}
else
{

$order = "DELETE FROM COMPANY_UPS WHERE COMPANY='$company_ups'";
$result = mysql_query($order);	//order executes
if($result)
{
 echo("UPS OID from company $company_ups Deleted");
}
else
{
 echo("Unable to Delete UPS OID");
}
}
}
}


else if(isset($_POST['Add_Oid_UPS']))
{
//var_dump($_POST);
if($company_ups=="")
{
echo("\nitems can't be empty");
}
else
{
$order = "SELECT * FROM COMPANY_UPS WHERE COMPANY='$company_ups'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
                  if(mysql_num_rows($result) >0)
                     {echo("OID from company $company_ups already exists");}
  else
  {
//inserting data order


  $order = "INSERT INTO COMPANY_UPS(CURRENT, VOLTAGE,CUR_UPS_LOAD ,REM_BATT_CAP,UPS_BATT_RUNTIME,COMPANY) VALUES('$current_ups','$voltage_ups','$current_ups_load','$rem_batt_capacity','$ups_batt_runtime','$company_ups')";
//declare in the order variable
$result = mysql_query($order);	//order executes
   if($result)
   { echo("OID Registration succeeded"); }
   else
     { echo("OID Registration failed");}
  }
}
}



else if(isset($_POST['View_Oid_UPS']))
{


if($company_ups=="")
{

$order = "SELECT * FROM COMPANY_UPS";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
                  if(mysql_num_rows($result) <=0)
                     {echo("OID table is empty"); exit;}

?>
<html>
<head>
<title> Server information</title>
</head>

<body bgcolor="#90EE90">
<table width="1200" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>id</th>
<th>CURRENT</th>
<th>VOLTAGE</th>
<th>CURRENT UPS LOAD</th>
<th>REMAINING BATTERY CAPACITY</th>
<th>BATTERY REMAINING RUNTIME</th>
<th>COMPANY</th>


<?php
$order = "SELECT * FROM COMPANY_UPS";
$result = mysql_query($order);	//order executes
while($row = mysql_fetch_assoc($result)) {
          

echo "<tr>"; 

echo "<td>". $row['id']."</td>";
echo "<td>". $row["CURRENT"]."</td>";
echo "<td>". $row["VOLTAGE"]."</td>";
echo "<td>". $row["CUR_UPS_LOAD"]."</td>";
echo "<td>". $row["REM_BATT_CAP"]."</td>";
echo "<td>". $row["UPS_BATT_RUNTIME"]."</td>";
echo "<td>". $row["COMPANY"]."</td>";
echo "</tr>";
                }
?>

</table>
<br>

</body>
</html>

<?php
exit;
}


//$result = $connect->query($order);

$order = "SELECT * FROM COMPANY_UPS WHERE COMPANY='$company_ups'";
$result = mysql_query($order);	//order executes


                  if(mysql_num_rows($result) <=0)
                     {echo("OID form company $company_ups doesn't exist"); exit;}
?>
<html>
<head>
<title> Server information</title>
</head>

<body bgcolor="#90EE90">
<table width="1200" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>id</th>
<th>CURRENT</th>
<th>VOLTAGE</th>
<th>CURRENT UPS LOAD</th>
<th>REMAINING BATTERY CAPACITY</th>
<th>BATTERY REMAINING RUNTIME</th>
<th>COMPANY</th>

<?php
$order = "SELECT * FROM COMPANY_UPS WHERE COMPANY='$company_ups'";
$result = mysql_query($order);	//order executes

while($row = mysql_fetch_assoc($result)) {
          

echo "<tr>"; 

echo "<td>". $row['id']."</td>";
echo "<td>". $row["CURRENT"]."</td>";
echo "<td>". $row["VOLTAGE"]."</td>";
echo "<td>". $row["CUR_UPS_LOAD"]."</td>";
echo "<td>". $row["REM_BATT_CAP"]."</td>";
echo "<td>". $row["UPS_BATT_RUNTIME"]."</td>";
echo "<td>". $row["COMPANY"]."</td>";
echo "</tr>";
                }
?>
</table>
<br>
</body>
</html>

<?php
exit;
}



?>



