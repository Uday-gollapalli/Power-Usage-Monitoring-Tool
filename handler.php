<?php
ini_set('display_errors',1);
$background_color = "Green";



include('config.php');



// Get values from form 
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$warning = $_POST['warning'];
$danger = $_POST['danger'];
$critical = $_POST['critical'];



if(isset($_POST['register']))
{

    if($name=="" | $email=="" | $phone=="" )
       { echo("items can't be empty");exit; }
//inserting data order
    else 
         {

  $order = "SELECT * FROM EMAIL WHERE EMAIL='$email'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
        if(mysql_num_rows($result) >0)
        
           {
              echo("HANDLER ALREADY EXISTS");
                echo '<br>';

              if($critical<$danger && $danger<$warning)
               {

$order="UPDATE EMAIL SET WARNING=$warning,DANGER=$danger,CRITICAL=$critical WHERE EMAIL='$email'";
  $result = mysql_query($order);	//order executes
               if($result)
                    {echo("handler data updated\n\n"); exit; }
                        else
                        {echo("hanlder updating failed"); }
                       
                }
           echo("enter values in such a way that critical < danger < warning.");
           exit;


            }
        else
            {

               if($critical<$danger && $danger<$warning)
               {
           $order = "INSERT INTO EMAIL(NAME,EMAIL,PHONE,WARNING,DANGER,CRITICAL) VALUES('$name','$email','$phone',$warning,$danger,$critical)";

          //declare in the order variable
                        $result = mysql_query($order);	//order executes
                        if($result)
                        {echo("handler Registered\n\n"); exit; }
                        else
                        {echo("hanlder registration failed"); }

              
                     }
               
                }
                  echo("cirical<danger< warning");
           exit;
          }

exit;
}



else if(isset($_POST['delete']))
{
//var_dump($_POST);
if($email=="")
       {
        echo("email address can't be empty");
        exit;
       }
      
 $order = "SELECT * FROM EMAIL WHERE EMAIL.EMAIL='$email'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
  if(mysql_num_rows($result) <=0)
      {echo("HANDLER doesn't exist");}
  else
     {
       
 $order = "DELETE FROM EMAIL WHERE EMAIL='$email'";
        $result = mysql_query($order);	//order executes
         if($result)
          {echo("HANDLER Deleted");}
         else
          {echo("Unable to Delete HANDLER");}

     }
exit;
}


if(isset($_POST['view']))
{


?>
<html>
<head>
<title> Device information</title>
</head>

<body bgcolor="#90EE90">
<h3>THRESHOLD CONFIGURATION</h3>
<form id ="form" method="post" action="select_devices.php">
<table width="1200" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>id</th>
<th>EMAIL</th>
<th>NAME</th>
<th>PHONE</th>
<th>WARNING</th>
<th>DANGER</th>
<th>CRITICAL</th>

<?php
$order = "SELECT * FROM EMAIL";
$result = mysql_query($order);	//order executes
while($row = mysql_fetch_assoc($result)) {
          

echo "<tr>"; 

echo "<td>". $row['id']."</td>";
echo "<td>". $row["EMAIL"]."</td>";
echo "<td>". $row["NAME"]."</td>";
echo "<td>". $row["PHONE"]."</td>";
echo "<td>". $row["WARNING"]."</td>";
echo "<td>". $row["DANGER"]."</td>";
echo "<td>". $row["CRITICAL"]."</td>";

echo "</tr>";
                }
?>

</table>
<br>


<?php
exit;
}




?>
