<?php

header("Contenet-Type:application/json");
$req;
$response= array ();
if(!empty($_GET['req']))
{
#$epoch = time();
#echo "$epoch\n";
#$half_day = $epoch - 43200;
#echo "Half Day.' '.$half_day";

$req = $_GET['req'];
$req_arr = explode('.',$req);
$req_data = $req_arr[0];

if ($req_data == 'power')
{
deliver_response($req);

}



}else
{
echo "it is not a valid request!!\n";
}

function deliver_response ($req)
{
#header("HTTP/1.1 $status, $status_message");
#$response['status'] = $status;
#$response['status_message'] = $status_message;



$file_handle = fopen("$req.txt", "rb");
while (!feof($file_handle)) {
$epoch = time();
$half_day = $epoch - 86400*4;

$line_of_text = fgets($file_handle);
$parts = explode(' ', $line_of_text);

if($parts[0] > $half_day)
{
$response["$parts[0]"] = $parts[1];
#print_r($parts[0]);
}
}
#$response['req'] = "$req";
$json_response = json_encode ($response);
echo "$json_response\n";


}
?>
