<?php


//declaration of input file 
$login_file = "db.conf";
//echo $login_file;

//declaration of variables
$line = 0;

$handle = fopen("$login_file", "r");
if ($handle) {
//while loop for reading text lines and extract the
while (($string = fgets($handle)) !== false) {

$line++;

preg_match('/"([^"]+)"/', $string, $value);

//echo $value[1];

if ($line == 1){
$host = $value[1];
}
if ($line == 2){
$dpport = $value[1];
}
if ($line == 3){
$database = $value[1];
}
if ($line == 4){
$username = $value[1];
}
if ($line == 5){
$password = $value[1];
}
}
fclose($handle);
} 
else {
echo "error opening the log file.";
}
$connect=mysql_connect($host,$username,$password);//database connection
mysql_select_db($database);
//echo("connected\n");
?>
