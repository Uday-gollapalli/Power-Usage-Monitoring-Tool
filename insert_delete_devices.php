<!DOCTYPE HTML>
<html>
<head>
<title> Device-Server monitoring</title>
</head>
<body bgcolor="#ADD8E6">


<div style="float:right">
<form align="right" name="form1" method="post" action="index.php">
  <label class="logoutLblPos">
  <input name="submit2" type="submit" id="submit2" value="log out">
  </label>
</form>
</div>

<?php
ini_set('display_errors',1);




include('config.php');



// Get values from form 
$id = $_POST['id'];
$ip = $_POST['IP_address'];
$port = $_POST['PORT_address'];
$community = $_POST['community'];
$devices = $_POST['devices'];
$id_pdu = $_POST['pdu'];
#$interfaces = $_POST['interfaces'];
#$myval=explode(',',$interfaces);

#foreach($myval as $value)
#{
#echo "$value\n";
#} 
if(isset($_POST['Delete_Device']))
{
//var_dump($_POST);
if($id=="" | $ip=="" | $port=="" | $community=="")
       {
        echo("items can't be empty");
        exit;
       }
      
 $order = "SELECT * FROM UPS WHERE id='$id'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
  if(mysql_num_rows($result) <=0)
      {echo("UPS doesn't exist"); exit;}

        
 $order = "SELECT * FROM UPS WHERE IP='$ip' and  PORT=$port and COMMUNITY='$community'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
       if(mysql_num_rows($result) <=0)
      {echo("UPS doesn't exist"); exit;}
  else
     {
       
 $order = "DELETE FROM UPS WHERE id='$id'";
        $result = mysql_query($order);	//order executes
         if($result)
          {echo("Device Deleted");}
         else
          {echo("Unable to Delete Device");}
 $order = "DELETE FROM PDU_UPS WHERE UPS_ID='$id'";
        $result = mysql_query($order);	//order executes
         if($result)
          {echo("UPS PDU DELETED Deleted");}
         else
          {echo("Unable to Delete Device");}
     }
exit;
}



else if(isset($_POST['Query']))
{

    if($id=="" | $ip=="" | $port=="" | $community=="")
       {
        echo("items can't be empty");
       exit;
       }
//inserting data order
    else 
         {

  $order = "SELECT * FROM UPS WHERE IP='$ip' AND PORT=$port AND COMMUNITY='$community'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
                  if(mysql_num_rows($result) >0)
                     {echo("UPS already exists");}
                  else
                     {
           $order = "INSERT INTO UPS(id,IP, PORT,COMMUNITY) VALUES('$id','$ip','$port','$community')";

          //declare in the order variable
           $result = mysql_query($order);	//order executes
          if($result)
             {echo("Input data is succeed\n\n"); }
          else
            {echo("Inserting data failed. may be UPS ID REPEATED"); }

               $order = "SELECT * FROM PDU_UPS WHERE PDU_ID='$id_pdu' AND UPS_ID='$id'" ;
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
                  if(mysql_num_rows($result) >0)
                     {exit;}
             $order = "INSERT INTO PDU_UPS(PDU_ID, UPS_ID,DEVICES) VALUES('$id_pdu','$id','$devices')";

          //declare in the order variable
           $result = mysql_query($order);	//order executes
          if($result)
             {echo("Input data is succeed\n\n"); }
          else
            {echo("Inserting data failed"); }
         }
      }

exit;
}

else if(isset($_POST['View_Device']))
{


if($id==""| $ip=="" | $port=="" | $community=="")
{?>
<html>
<head>
<title> Device information</title>
</head>

<body bgcolor="#90EE90">
<form id ="form" method="post" action="select_devices.php">
<table width="1200" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>id</th>
<th>IP</th>
<th>PORT</th>
<th>COMMUNITY</th>
<th>POWER/WATTS</th>
<th>LOAD/kWh</th>
<th>BATTERY CAPACITY/%</th>
<th>BATTERY INDICATOR</th>
<th>RUN TIME</th>
<th>PLOTT</th>
<th>VIEW MAP</th>
<?php
$order = "SELECT * FROM UPS";
$result = mysql_query($order);	//order executes
while($row = mysql_fetch_assoc($result)) {
          
$var=$row["REM_BATT_CAP"];
echo "<tr>"; 

echo "<td>". $row['id']."</td>";
echo "<td>". $row["IP"]."</td>";
echo "<td>". $row["PORT"]."</td>";
echo "<td>". $row["COMMUNITY"]."</td>";
echo "<td>". $row["POWER"]."</td>";
echo "<td>". $row["CUR_UPS_LOAD"]."</td>";
echo "<td>". $row["REM_BATT_CAP"]."</td>";
echo "<td><meter value=$var min=0 max=100></meter></td>";
echo "<td>". $row["UPS_BATT_RUNTIME"]."</td>";
echo '<td><a href="select_devices.php?id='.$row['id'].'">PLOT_'.$row['id'].'</a></td>';
echo '<td><a href="mapper.php?id='.$row['id'].'">MAP_'.$row['id'].'</a></td>';
echo "</tr>";
                }
?>

</table>
<br>


<?php
exit;
}


//$result = $connect->query($order);
?>
<html>
<head>
<title> Device information</title>
</head>

<body bgcolor="#90EE90">
<form id ="form2" method="post" action="select_devices.php">
<table width="1200" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>id</th>
<th>IP</th>
<th>PORT</th>
<th>COMMUNITY</th>
<th>POWER</th>
<th>LOAD</th>
<th>CAPACITY</th>
<th>CAP_IND.</th>
<th>RUN TIME</th>
<th>PLOTT</th>
<th>VIEW MAP</th>

<?php
$order = "SELECT * FROM UPS WHERE IP='$ip'AND PORT=$port AND COMMUNITY='$community'";
$result = mysql_query($order);	//order executes
while($row = mysql_fetch_assoc($result)) {
          
//echo "id: " .$row["id"]. "-IP: " . $row["IP"] . " PORT: ". $row["PORT"]. "COMMUNITY:". $row["COMMUNITY"]. "INTERFACES: " . $row["INTERFACES"]. "<br>";

$var2=$row["REM_BATT_CAP"];
echo "<tr>"; 

echo "<td>". $row['id']."</td>";
echo "<td>". $row["IP"]."</td>";
echo "<td>". $row["PORT"]."</td>";
echo "<td>". $row["COMMUNITY"]."</td>";
echo "<td>". $row["POWER"]."</td>";
echo "<td>". $row["CUR_UPS_LOAD"]."</td>";
echo "<td>". $row["REM_BATT_CAP"]."</td>";
echo "<td><meter value=$var2 min=0 max=100></meter></td>";
echo "<td>". $row["UPS_BATT_RUNTIME"]."</td>";
echo '<td><a href="select_devices.php?id='.$row['id'].'">PLOT_'.$row['id'].'</a></td>';
echo '<td><a href="mapper.php?id='.$row['id'].'">MAP_'.$row['id'].'</a></td>';
echo "</tr>";
                }
?>
</table>
<br>
</head>


<?php
exit;
}

?>
</table>
<br>
<title> Device information</title>
</head>

</fieldset>










