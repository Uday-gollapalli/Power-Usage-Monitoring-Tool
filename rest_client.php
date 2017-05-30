<?php
echo "<html>";
echo "<body style=\"background-color:lightgreen\">";
echo "</body>";
echo "</html>";
#$url1=$_SERVER['REQUEST_URI'];
#header("Refresh: 5; URL=$url1");
if (isset($_POST['rest']))
{
$rest = $_POST['rest'];
//echo "$rest";
if (!empty($rest))
{
$url = "$rest";
$url_arr = explode ("/",$url );
$req = "?req=$url_arr[5]";
$url_real = "$url_arr[0]//$url_arr[2]/$url_arr[3]/$url_arr[4]/?req=$url_arr[5]";

$power_rrd = "$url_arr[5].rrd";
$powercli_txt = "$url_arr[5]cli.txt";
$timestamp_txt = "timestamp.$url_arr[5].txt";


if(file_exists($power_rrd))
{
echo "<h4>Previous record already exits for this device </h4>";
echo "<h4>The new imported set of data is: </h4>";
}
else
{

echo "<h4>New record created for this device </h4>";
echo "<h4>The new imported set of data is: </h4>";

$_opts = array( "--step", 60, "--start", 0,
                 "DS:power:GAUGE:120:0:U",
                 "RRA:AVERAGE:0.5:1:576", 
                 "RRA:AVERAGE:0.5:6:672", 
                 "RRA:AVERAGE:0.5:24:720", 
                 "RRA:AVERAGE:0.5:576:365"
                 
               );

  $ret = rrd_create("$url_arr[5].rrd", $_opts);

  if ( $ret == 0 )
  {
      $err = rrd_error();
      echo "Create error: $err\n";
  }
else
{
$timestamp = time();
#echo "<h2>$timestamp</h2>";
$myfile = fopen("$timestamp_txt", "w") or die("Unable to open file!");
fwrite($myfile, $timestamp);
fclose($myfile);

}

}
#echo "$url_real";

$req_arr = explode ('.',$url_arr[5]);
$req_data = $req_arr[0];

if ($req_data)
{

$power = curl_init($url_real);
curl_setopt($power,CURLOPT_RETURNTRANSFER,1);
$response_power = curl_exec($power);
if (!$response_power)
{
echo "The remote host is not responding\n";
exit();
}


$result_power = json_decode($response_power);

$rrd_data = array();


foreach ($result_power as $key => $value)
{

$unsind_int = (int)$value;
if($unsind_int)
{

$myfile = fopen("$timestamp_txt", "r") or die("Unable to open file!");
$timestamp1 = fgets($myfile);
fclose($myfile);

if ($key > $timestamp1) {

echo "<h5>$key $unsind_int</h5>";

$myfile = fopen($powercli_txt, "a") or die("Unable to open file!");
fwrite($myfile, "$key $unsind_int\n");
fclose($myfile);

$rrd_data[] =  "$key:$unsind_int";

/*
$unsind_int = (int)$value;
if($unsind_int)
{

$rrd_data[] =  "$key:$unsind_int";

}
*/


$myfile = fopen("$timestamp_txt", "w") or die("Unable to open file!");
fwrite($myfile, $key);
fclose($myfile);


}

}

}


#print_r($rrd_data);

$power_rrd = "$url_arr[5].rrd";

$sizerrd_data = count($rrd_data);
echo "<h4>$sizerrd_data new data set entries received</h4>";

if ($sizerrd_data > 0 ) {

if(file_exists($power_rrd))
{
$ret = rrd_update("$url_arr[5].rrd",$rrd_data);

if( $ret == 0 )
    {
      $err = rrd_error();
      echo "ERROR occurred: $err\n";
      exit();
    }
else
{
echo "<h4>Restful service successifully updated the local database. Enter device details of restful data and click view graph</h4>\n";
}

}else
{ 
$_opts = array( "--step", 60, "--start", 0,
                 "DS:power:GAUGE:120:0:U",
                 "RRA:AVERAGE:0.5:1:576", 
                 "RRA:AVERAGE:0.5:6:672", 
                 "RRA:AVERAGE:0.5:24:720", 
                 "RRA:AVERAGE:0.5:576:365"
                 
               );

  $ret = rrd_create("$url_arr[5].rrd", $_opts);

  if ( $ret == 0 )
  {
      $err = rrd_error();
      echo "Create error: $err\n";
  }
$ret = rrd_update("$url_arr[5].rrd",$rrd_data);

 if( $ret == 0 )
    {
      $err = rrd_error();
      echo "ERROR occurred: $err\n";
      exit();
    }
else
{
echo "<h4>Restful service successifully updated the local database. Enter device details of restful data and click view graph</h4>\n";
}
}
}
else {
echo "<h4>Please wait for at least one minute before requesting new data set</h4>\n";
}



}else
{
echo "<h4>Please Enter the URL in the way specified!</h4>\n";
}

}
}


?>
