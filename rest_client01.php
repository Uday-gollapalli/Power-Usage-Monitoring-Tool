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

$rrd_data[] =  "$key:$unsind_int";
}
}
#print_r($rrd_data);
$power_rrd = "$url_arr[5].rrd";


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
echo "Restful service successifull enter restful data and click view graph\n";
}

}else
{ 
$_opts = array( "--step", 10, "--start", 0,
                 "DS:power:COUNTER:20:0:U",
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
echo "Restful service successifull enter restful data and click view graph\n";
}

}


}else
{
echo "Please Enter the URL in the way specified!\n";
}

}
}


?>
