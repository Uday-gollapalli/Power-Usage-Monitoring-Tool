<!DOCTYPE HTML>
<html>
<head>
</head>
<body bgcolor="#90EE90">
<title> User registration</title>


<div style="float:right">
<form align="right" name="form1" method="post" action="index.php">
  <label class="logoutLblPos">
  <input name="submit2" type="submit" id="submit2" value="log out">
  </label>
</form>
</div>

<?php
ini_set('display_errors',1);
$background_color = "Green";



include('config.php');



// Get values from form 
$username = $_POST['user_name'];
$password_text = $_POST['pass_word'];
$previlage = $_POST['previlage'];

$salt = 'db4968a3db5f6ed2f60073c747bb4fb5';

$hash = md5($salt . $password_text); // Value: db4968a3db5f6ed2f60073c747bb4fb5

$password = $hash;


if(isset($_POST['delete_user']))
{
//var_dump($_POST);
if($username=="")
       {
        echo("items can't be empty");
        exit;
       }

else
{
 $order = "SELECT * FROM USER WHERE USERNAME='$username'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
  if(mysql_num_rows($result) <=0)
      {echo("User doesn't exist");}
  else
     {
       
 $order = "DELETE FROM USER WHERE USERNAME='$username'";
        $result = mysql_query($order);	//order executes
         if($result)
          {echo("User Deleted");}
         else
          {echo("Unable to Delete user");}
     }
exit;
}
}
else if(isset($_POST['register_user']))
{

    if($username=="" || $password=="" || $previlage=="")
       {
        echo("items can't be empty");
       exit;
       }
//inserting data order


    else
         {

  $order = "SELECT * FROM USER WHERE USERNAME='$username'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
                  if(mysql_num_rows($result) >0)
                     {echo("UPS already exists");}
                  else
                     {
           $order = "INSERT INTO USER(USERNAME, PASSWORD,TYPE) VALUES('$username','$password','$previlage')";

          //declare in the order variable
           $result = mysql_query($order);	//order executes
          if($result)
             {
               echo("Input data is succeed\n\n");
             }
          else
            {
            echo("Inserting data failed");
           }
         }
      }
exit;
}



else if(isset($_POST['view_user']))
{


if($username=="")
{
?>
<html>
<head>
<title> User information</title>
</head>

<body bgcolor="#90EE90">



<table width="600" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>id</th>
<th>USERNAME</th>
<th>PREVILAGE</th>


<?php
$order = "SELECT id,USERNAME,TYPE FROM USER";
$result = mysql_query($order);	//order executes


while($row = mysql_fetch_assoc($result)) {
          
if(mysql_num_rows($result) <=0)
      {echo("UPS doesn't exist");exit;}
echo "<tr>"; 

echo "<td>". $row['id']."</td>";
echo "<td>". $row["USERNAME"]."</td>";
echo "<td>". $row["TYPE"]."</td>";
echo "</tr>";
                }
?>
</table>

<br>

<legend>EDIT USER DETAILS:</legend>
<form id="form6" method="post" action="edit_del_user.php">
<fieldset title="address">

<label for = "id"> USER ID:</label>
<input type="text" id="id" name="id" size=5>

<label for = "user_name"> NEW USERNAME:</label>
<input type="text" id="user_name" name="user_name" size=10>

<label for = "pass_word"> NEW PASSWORD:</label>
<input type="password" id="pass_word" name="pass_word" size=8>


<select name="previlage">
  <option value=""></option>
  <option value="admin">admin</option>
  <option value="user">user</option>
  <option value="guest">guest</option>
</select>



<br>
<input type="submit" name="edit_user" value="edit User" class="button">
<input type="submit" name="delete_user" value="Delete User" class="button">
<input type="reset" name="reset" value="reset">


</fieldset>
</form> 

<?php
exit;
}



$order = "SELECT id,USERNAME,TYPE FROM USER WHERE USERNAME='$username'";
$result = mysql_query($order);	//order executes
 if(mysql_num_rows($result) <=0)
{echo "user doesn't exist";
exit;}
//$result = $connect->query($order);
?>
<html>
<head>
<title> User information</title>
</head>

<body bgcolor="#90EE90">



<form id ="form" method="post">
<table width="600" border="1" cellpadding="1" cellspacing="1">
<tr>
<th>id</th>
<th>USERNAME</th>
<th>PREVILAGE</th>

<?php
$order = "SELECT id,USERNAME,TYPE FROM USER WHERE USERNAME='$username'";
$result = mysql_query($order);	//order executes

while($row = mysql_fetch_assoc($result)) {
echo "<tr>"; 

echo "<td>". $row['id']."</td>";
echo "<td>". $row["USERNAME"]."</td>";
echo "<td>". $row["TYPE"]."</td>";
echo "</tr>";
                }

?>
</table>
<br>

<legend>EDIT USER DETAILS:</legend>
<form id="form3" method="post" action="edit_del_user.php">
<fieldset title="address">

<label for = "id"> USER ID:</label>
<input type="text" id="id" name="id" size=5>

<label for = "user_name"> NEW USERNAME:</label>
<input type="text" id="user_name" name="user_name" size=10>

<label for = "pass_word"> NEW PASSWORD:</label>
<input type="password" id="pass_word" name="pass_word" size=8>


<select name="previlage">
  <option value=""></option>
  <option value="admin">admin</option>
  <option value="user">user</option>
  <option value="guest">guest</option>
</select>



<br>
<input type="submit" name="edit_user" value="edit User" class="button">
<input type="submit" name="delete_user" value="Delete User" class="button">
<input type="reset" name="reset" value="reset">


</fieldset>
</form> 

<?php
exit;
}


?>







