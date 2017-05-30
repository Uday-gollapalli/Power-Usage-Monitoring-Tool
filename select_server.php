
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

function per_deviceplot($start,$id)
{
    $database = "pdu.$id.rrd";
    $imgfile = "pdu.$id.$start.gif";
    $opts = array( "--start", "$start",
        "--vertical-label", "PDU-$id",
        "--width", "400",
        "DEF:power_watt=$database:power_watt:AVERAGE",
        "DEF:power_va=$database:power_va:AVERAGE",
        "DEF:load=$database:load:AVERAGE",
"LINE1:power_watt#33FF00:power in watt",
"LINE2:power_watt#33FF00",
"GPRINT:power_watt:MAX:  Max\\: %5.1le %s",
"GPRINT:power_watt:AVERAGE: Avg\\: %5.1le %S",
"GPRINT:power_watt:LAST: Current\\: %5.1le %Sbps\\n",

"LINE1:power_va#FF0000:power in var",
"LINE2:power_va#FF0000",
"GPRINT:power_va:MAX:  Max\\: %5.1le %s",
"GPRINT:power_va:AVERAGE: Avg\\: %5.1le %S",
"GPRINT:power_va:LAST: Current\\: %5.1le %Sbps\\n",

"LINE1:load#0033FF:load",
"LINE2:load#0033FF",
"GPRINT:load:MAX:  Max\\: %5.1le %S",
"GPRINT:load:AVERAGE: Avg\\: %5.1le %S",
"GPRINT:load:LAST: Current\\: %5.1le %Sbps",
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




    $order = "SELECT * FROM PDU WHERE ID='$id'";
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


?>
