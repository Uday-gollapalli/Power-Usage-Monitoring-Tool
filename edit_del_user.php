<!DOCTYPE HTML>
<html>
<head>
</head>
<body bgcolor="#90EE90">
<title> adit and delete user</title>

<?php

$background_color = "Green";



include('config.php');



// Get values from form 
$id = $_POST['id'];
$username = $_POST['user_name'];
$password_text = $_POST['pass_word'];
$previlage = $_POST['previlage'];

$salt = 'db4968a3db5f6ed2f60073c747bb4fb5';

$hash = md5($salt . $password_text); // Value: db4968a3db5f6ed2f60073c747bb4fb5

$password = $hash;

ini_set('display_errors',1);

if(isset($_POST['delete_user']))
{
//var_dump($_POST);
if($id=="")
       {
        echo("items can't be empty");
        exit;
       }

else
{
 $order = "SELECT * FROM USER WHERE id='$id'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
  if(mysql_num_rows($result) <=0)
      {echo("User doesn't exist");}
  else
     {
       
 $order = "DELETE FROM USER WHERE id='$id'";
        $result = mysql_query($order);	//order executes
         if($result)
          {echo("User Deleted");}
         else
          {echo("Unable to Delete User");}
     }
exit;
}
}
else if(isset($_POST['edit_user']))
{

    if($id=="" || $username=="" || $password=="" || $previlage=="")
       {
        echo("items can't be empty");
       exit;
       }
//inserting data order


    else
         {

  $order = "SELECT * FROM USER WHERE id='$id'";
                 //declare in the order variable
                   $result = mysql_query($order);	//order executes
                  if(mysql_num_rows($result) >0)
                     {

           $order = "UPDATE USER SET USERNAME='$username', PASSWORD='$password',TYPE='$previlage' WHERE id=$id";

          //declare in the order variable
           $result = mysql_query($order);	//order executes
          if($result)
             {
               echo("updating user is succeed\n\n");
             }
          else
            {
            echo("updating user failed");
           }
}
                  else
                     {echo("user doesn't exists");

         }
      }
exit;
}



?>







