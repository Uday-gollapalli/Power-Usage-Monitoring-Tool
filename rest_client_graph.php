<?php
echo "<html>";
echo "<body style=\"background-color:lightgreen\">";
echo "</body>";
echo "</html>";
#$url1=$_SERVER['REQUEST_URI'];
#header("Refresh: 5; URL=$url1");

if (isset($_POST['rest_data']))
{
$rest_data = $_POST['rest_data'];
//echo "$rest";
if (!empty($rest_data))
{



function create_graph($output, $start, $title, $rrd_name1) {
  $options = array(
    "--slope-mode",
    "--start", "$start",
    "--title=$title",
    "--vertical-label=power in watts",
    "--width=800",
    "--lower=0",
    "--alt-y-mrtg",
    "--force-rules-legend",
    "DEF:Power=$rrd_name1.rrd:power:AVERAGE",
    "AREA:Power#00FF00:POWER\l",
    "GPRINT:Power:AVERAGE:POWER\: %6.2lf",
    
);
$ret = rrd_graph($output, $options);
  if (! $ret) {
    echo "Error\n";
    #print_r(rrd_error());
}
}

create_graph("d.$rest_data.png", "-1d", "`Daily` Graph (5 Minute Averge)","$rest_data");
create_graph("w.$rest_data.png", "-1w", "`Weakly` Graph (30 Minute Average)","$rest_data");
create_graph("m.$rest_data.png", "-1m", "`Monthly` Graph (2 Hour Average)","$rest_data");
create_graph("y.$rest_data.png", "-1y", "`Yearly` Graph (1 Day Average)","$rest_data");

echo "<html>";
echo "<head>";
echo "<meta http-equiv=\"refresh\" content=\"300\">";
echo "</head>";
echo "<body>";
echo  "<table border=\"1\" style=\"width: 100%;\" CELLPADDING=10 CELLSPACING=1 RULES=ROWS FRAME=HSIDES>";
echo "<tr>";
echo "<td><img src='d.$rest_data.png' height='90%' /></td>";
echo "<td><img src='w.$rest_data.png' height='90%' /></td>";
echo "</tr>";
echo "<tr>";
echo "<td><img src='m.$rest_data.png' height='90%' /></td>";
echo "<td><img src='y.$rest_data.png' height='90%' /></td>";
echo "</tr>";
echo "</table>";
echo "</body>";
echo "</html>";


}
}

?>
