<?php
ini_set('display_errors',1);

//the example of inserting data with variable from HTML form
#mysql_connect("localhost","root","root");//database connection
#mysql_select_db("addishiwot");
include('config.php');
// Get values from form 
$id = $_POST['id'];
$ip = $_POST['Server_IP_address'];
$port = $_POST['Server_PORT_address'];
$community=$_POST['Server_COMMUNITY'];



if(isset($_POST['Delete_Server']))
{
//var_dump($_POST);
if($id=="" || $ip=="" || $port=="" || $community=="")
  { echo("\nitems can't be empty");echo("<br>");}
else
{

$order = "SELECT * FROM PDU WHERE IP='$ip' AND PORT=$port AND COMMUNITY='$community'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
                  if(mysql_num_rows($result) <=0)
                     {echo("PDU doesn't exist");echo("<br>"); }

   else
   {


$order = "DELETE FROM PDU WHERE id='$id'";
$result = mysql_query($order);	//order executes
   if($result)
      { echo("PDU Deleted");echo("<br>");} else { echo("Unable to Delete PDU");echo("<br>");}

$order2 = "SELECT * FROM PDU_UPS WHERE PDU_ID='$id'";
   $result2 = mysql_query($order2);	//order executes
if($result2)
{
        while($row = mysql_fetch_assoc($result2))
          {
           $ups_id = $row['UPS_ID'];
           #echo("$ups_id");
           echo("<br>");

            $order3 = "DELETE FROM PDU_UPS WHERE PDU_ID='$id'";
            $result3 = mysql_query($order3);	//order executes
            if($result3) {echo("PDU_UPS DELETED");echo("<br>");} else {echo("Unable to Delete PDU-UPS");echo("<br>");}

           $order4 = "DELETE FROM UPS WHERE id='$ups_id'";
           $result4 = mysql_query($order4);	//order executes
           if($result4) {echo("UPS DELETED");echo("<br>");} else {echo("Unable to Delete UPS");echo("<br>");}
      
          }
}

      }
   }
}

else if(isset($_POST['Add_Server']))
{
//var_dump($_POST);
if($id=="" || $ip=="" || $port=="" || $community=="")
{
echo("\nitems can't be empty");
exit;
}
else
{
$order = "SELECT * FROM PDU WHERE IP='$ip' AND PORT=$port AND COMMUNITY='$community'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
                  if(mysql_num_rows($result) >0)
                     {echo("PDU already exists");}
  else
  {
//inserting data order
$order = "INSERT INTO PDU(id,IP, PORT,COMMUNITY) VALUES('$id','$ip',$port,'$community')";

//declare in the order variable
$result = mysql_query($order);	//order executes
   if($result)
   { echo("Registration succeeded");exit;}
   else
     { echo("Registration failed"); exit;}


}
                
}
}


else if(isset($_POST['View_Server']))
{


if($ip=="" | $port=="" | $community=="")
{
?>
<html>
<head>
<title> Server information</title>
</head>


<body bgcolor="#90EE90">

<div style="float:right">
<form align="right" name="form1" method="post" action="index.php">
  <label class="logoutLblPos">
  <input name="submit2" type="submit" id="submit2" value="log out">
  </label>
</form>
</div>

<form id ="form" method="post" action="select_server.php">
<table width="600" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>id</th>
<th>IP</th>
<th>PORT</th>
<th>COMMUNITY</th>
<th>POWER(WATT)</th>
<th>POWER(VAR)</th>
<th>PDU_LOAD</th>
<th>PLOTT</th>
<th>VIEW MAP</th>
<?php
$order = "SELECT * FROM PDU";
$result = mysql_query($order);	//order executes
while($row = mysql_fetch_assoc($result)) {
          

echo "<tr>"; 

echo "<td>". $row['id']."</td>";
echo "<td>". $row["IP"]."</td>";
echo "<td>". $row["PORT"]."</td>";
echo "<td>". $row["COMMUNITY"]."</td>";
echo "<td>". $row["POWER_WATT"]."</td>";
echo "<td>". $row["POWER_VA"]."</td>";
echo "<td>". $row["PDU_LOAD"]."</td>";

echo '<td><a href="select_server.php?id='.$row['id'].'">PLOT_'.$row['id'].'</a></td>';
echo '<td><a href="mapper.php?id='.$row['id'].'">MAP_'.$row['id'].'</a></td>';
echo "</tr>";
                }
?>

</table>
<br>

</form>
</body>
</html>

<?php
exit;
}


//$result = $connect->query($order);
?>
<html>
<head>
<title> Server information</title>
</head>

<body bgcolor="#90EE90">
<form id ="form2" method="post" action="select_server.php">
<table width="600" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>id</th>
<th>IP</th>
<th>PORT</th>
<th>COMMUNITY</th>

<th>POWER(WATT)</th>
<th>POWER(VAR)</th>
<th>PDU_LOAD</th>
<th>PLOTT</th>
<th>VIEW MAP</th>

<?php
$order = "SELECT * FROM PDU WHERE IP='$ip'AND PORT=$port AND COMMUNITY='$community'";
$result = mysql_query($order);	//order executes
while($row = mysql_fetch_assoc($result)) {
          
//echo "id: " .$row["id"]. "-IP: " . $row["IP"] . " PORT: ". $row["PORT"]. "COMMUNITY:". $row["COMMUNITY"]. "INTERFACES: " . $row["INTERFACES"]. "<br>";
echo "<tr>"; 

echo "<td>". $row['id']."</td>";
echo "<td>". $row["IP"]."</td>";
echo "<td>". $row["PORT"]."</td>";
echo "<td>". $row["COMMUNITY"]."</td>";

echo "<td>". $row["POWER_WATT"]."</td>";
echo "<td>". $row["POWER_VA"]."</td>";
echo "<td>". $row["PDU_LOAD"]."</td>";
echo '<td><a href="select_server.php?id='.$row['id'].'">PLOT_'.$row['id'].'</a></td>';
echo '<td><a href="mapper.php?id='.$row['id'].'">MAP_'.$row['id'].'</a></td>';
echo "</tr>";
                }
?>

</table>
<br>
<title> Server Information</title>
<br><br><br>
</form>
</body>
</html>

<?php
exit;
}
?>
