
<!DOCTYPE HTML>
<html>
<head>
<title>Traffic-Analysis</title>
</head>
<body bgcolor="#E6E6FA">
<title> Device-Server monitoring</title>
</body>
</html>
<?php
ini_set('display_errors',1);
include('config.php');
 

$id = $_GET['id'];
echo($id);

function per_deviceplot($start,$id)
{
    $database = "ups.$id.rrd";
    $imgfile = "ups.$id.$start.gif";
    $opts = array( "--start", "$start",
        "--vertical-label", "UPS-$id",
        "--width", "400",
        "DEF:power=$database:power:AVERAGE",
        "DEF:cur_UpsLoad=$database:cur_UpsLoad:AVERAGE",
        "DEF:rem_batteryCapacity=$database:rem_batteryCapacity:AVERAGE",
"LINE1:power#33FF00:POWER in watt",
"LINE2:power#33FF00",
"GPRINT:power:MAX:  Max\\: %5.1le %s",
"GPRINT:power:AVERAGE: Avg\\: %5.1le %S",
"GPRINT:power:LAST: Current\\: %5.1le %Sbps\\n",

"LINE1:cur_UpsLoad#FF0000:UPS LOAD",
"LINE2:cur_UpsLoad#FF0000",
"GPRINT:cur_UpsLoad:MAX:  Max\\: %5.1le %s",
"GPRINT:cur_UpsLoad:AVERAGE: Avg\\: %5.1le %S",
"GPRINT:cur_UpsLoad:LAST: Current\\: %5.1le %Sbps\\n",

"LINE1:rem_batteryCapacity#0033FF:BATTERY CAPACITY",
"LINE2:rem_batteryCapacity#0033FF",
"GPRINT:rem_batteryCapacity:MAX:  Max\\: %5.1le %S",
"GPRINT:rem_batteryCapacity:AVERAGE: Avg\\: %5.1le %S",
"GPRINT:rem_batteryCapacity:LAST: Current\\: %5.1le %Sbps",
"HRULE:0#000000");

    make_graph($imgfile, $opts);
}

function make_graph($file, $options)
{
    $ret = rrd_graph("$file", $options);

    ## if $ret is an array, then rrd_graph was successful
    ##
    if(is_array($ret) ) {
        echo "<img src=\"$file\" border=0>";
    }
    else {
        $err = rrd_error();
        echo "<p><b>$err</b></p>";
    }
}




#if(isset($_POST['traffic_per_device']))
#{

#if($id!="")
#{
    $order = "SELECT * FROM UPS WHERE ID='$id'";
    //declare in the order variable
    $result = mysql_query($order);	//order executes

       if(mysql_num_rows($result) > 0)
          {
           while($row=mysql_fetch_array($result))
             {
               $id= $row['id'];
                
              }
       # require "graph_server.php";
        print "<h2>Daily graph</h2>\n";
      per_deviceplot("-1d","$id");
        print "<h2>Weekly graph</h2>\n";
      per_deviceplot("-1w","$id");
        print "<h2>Monthly graph</h2>\n";
       per_deviceplot("-1m","$id");
      }   
    else{
         echo("Unregistered IP\n");
         exit;
       } 
#}
exit;
#}



?>
