#!/usr/bin/perl

use strict;
use warnings;
use DBI;
#use SNMP;
use Net::SNMP;
use RRDs;
use Data::Dumper;
#use Mail::Sendmail;
$SIG{CHLD} = 'IGNORE';
#use MIME::Lite;
# use Email::Send;
# use Email::Send::Gmail;
# use Email::Simple::Creator;

open(FH, "<db.conf") || die "Can't access login credentials";

my $t;
my @array;

for $t(1..5)
{
my @read_line = split(/=/,<FH>);
my $data = $read_line[1];
$data = string_extractor($data);
push(@array,$data);
}


my $host=$array[0];
my $port= $array[1];
my $database=$array[2];
my $username=$array[3];
my $password=$array[4];


                                            
my $dsn = "DBI:mysql:";
my $dbh = DBI->connect($dsn, $username, $password ) or die $DBI::errstr;
$dbh->do("create database if not exists $database");
$dbh->disconnect();

$dsn ="DBI:mysql:database=$database;
                    host= $host;
                    port= $port";
$dbh= DBI->connect($dsn, $username, $password, {RaiseError=>1,AutoCommit=>1});
$dbh->{InactiveDestroy} = 1;

#creating our table
$dbh->do("CREATE TABLE IF NOT EXISTS UPS( id varchar(11) NOT NULL UNIQUE, IP tinytext NOT NULL, PORT int(11) NOT NULL, COMMUNITY tinytext NOT NULL,  POWER int, CUR_UPS_LOAD int, REM_BATT_CAP int, UPS_BATT_RUNTIME tinytext,PRIMARY KEY(id));");


$dbh->do("CREATE TABLE IF NOT EXISTS PDU( id varchar(11) NOT NULL UNIQUE, IP tinytext NOT NULL, PORT int(11) NOT NULL, COMMUNITY tinytext NOT NULL, POWER_WATT tinyint, POWER_VA tinyint, PDU_LOAD tinyint, PRIMARY KEY(id));
");

$dbh->do("CREATE TABLE IF NOT EXISTS PDU_UPS( PDU_ID varchar(11) NOT NULL,UPS_ID varchar(11) NOT NULL,DEVICES tinytext);");

$dbh->do("drop table IF EXISTS EMAIL");

$dbh->do("CREATE TABLE IF NOT EXISTS EMAIL(
id int(11) NOT NULL AUTO_INCREMENT,
NAME tinytext ,
EMAIL tinytext NOT NULL,
PHONE tinytext NOT NULL,
WARNING tinyint,
DANGER tinyint,
CRITICAL tinyint,
PRIMARY KEY(id))ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");


$dbh->do("drop table IF EXISTS TRAP_MANAGER");

$dbh->do("CREATE TABLE IF NOT EXISTS TRAP_MANAGER(
id int(11) NOT NULL AUTO_INCREMENT,
IP tinytext NOT NULL,
PORT int(11) NOT NULL,
COMMUNITY tinytext NOT NULL,
WARNING tinyint,
DANGER tinyint,
CRITICAL tinyint,
PRIMARY KEY(id))ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");


$dbh->do("drop table IF EXISTS TRAP_TRACK");

$dbh->do("CREATE TABLE IF NOT EXISTS TRAP_TRACK(
id int(11) NOT NULL AUTO_INCREMENT,
IP tinytext NOT NULL,
PORT int(11) NOT NULL,
COMMUNITY tinytext NOT NULL,
STATUS tinytext NOT NULL,
PRIMARY KEY(id))ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");

$dbh->do("drop table IF EXISTS EMAIL_TRACK");

$dbh->do("CREATE TABLE IF NOT EXISTS EMAIL_TRACK(
id int(11) NOT NULL AUTO_INCREMENT,
IP tinytext NOT NULL,
PORT int(11) NOT NULL,
COMMUNITY tinytext NOT NULL,
EMAIL tinytext NOT NULL,
STATUS tinytext NOT NULL,
PRIMARY KEY(id))ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");



$dbh->do("drop table IF EXISTS USER");

$dbh->do("CREATE TABLE IF NOT EXISTS USER(
id int(11) NOT NULL AUTO_INCREMENT,
USERNAME tinytext NOT NULL,
PASSWORD tinytext NOT NULL,
TYPE tinytext NOT NULL,
PRIMARY KEY(id))ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");

my $sth;

my $sth_user = $dbh->prepare("SELECT * FROM USER"); 
$sth_user->execute();
my $rows = +$sth_user->rows;
$sth_user->finish();

#my $password_text = 'admin';
#my $salt = 'db4968a3db5f6ed2f60073c747bb4fb5';
#my $hash = md5($salt . $password_text); #Value: db4968a3db5f6ed2f60073c747bb4fb5
#my $hash = `echo "$salt . $password_text" | md5sum`;

my $admin_password = '79e9e4979c178ad7e3062dc534cfec05';


if ($rows == 0){
$sth =$dbh->prepare("INSERT INTO USER (USERNAME, PASSWORD, TYPE) VALUES ('admin', '$admin_password', 'admin');");
$sth->execute();
$sth->finish();
}

my ($warning_trap, $danger_trap, $critical_trap);

#getting battery capacity thresholds drop level to send trap
$sth = $dbh->prepare("SELECT WARNING, DANGER, CRITICAL FROM TRAP_MANAGER WHERE id=1;");
$sth->execute() or die $DBI::errstr;
$rows = +$sth->rows;
#print "Number of rows found :" ; print $rows; print "\n";

if($rows == 0){
$sth->finish();
print "no trap manager device configured \n";
}
else{
my @thresholds = $sth->fetchrow_array();
my ($warning_trap, $danger_trap, $critical_trap) = @thresholds;
$sth->finish();
print "warning=$warning_trap, danger=$danger_trap, critical=$critical_trap\n";
}

$dbh->do("drop table IF EXISTS COMPANY_UPS");

$dbh->do("CREATE TABLE IF NOT EXISTS COMPANY_UPS(
id int(11) NOT NULL AUTO_INCREMENT,
CURRENT tinytext NOT NULL,
VOLTAGE tinytext NOT NULL,
CUR_UPS_LOAD tinytext NOT NULL,
REM_BATT_CAP tinytext NOT NULL,
UPS_BATT_RUNTIME tinytext NOT NULL,
COMPANY varchar(100) NOT NULL,
PRIMARY KEY(id))ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");
$dbh->do("INSERT INTO COMPANY_UPS(CURRENT,VOLTAGE,CUR_UPS_LOAD,REM_BATT_CAP,UPS_BATT_RUNTIME,COMPANY) VALUES('.1.3.6.1.4.1.318.1.1.1.4.2.4.0','.1.3.6.1.4.1.318.1.1.1.4.2.1.0','.1.3.6.1.4.1.318.1.1.1.4.2.3.0','.1.3.6.1.4.1.318.1.1.1.2.2.1.0','.1.3.6.1.4.1.318.1.1.1.2.2.3.0','APC');");


$dbh->do("INSERT INTO COMPANY_UPS(CURRENT,VOLTAGE,CUR_UPS_LOAD,REM_BATT_CAP,UPS_BATT_RUNTIME,COMPANY) VALUES('1.3.6.1.4.1.674.10892.2.4.2.1.6','1.3.6.1.4.1.674.10892.2.4.2.1.5','1.3.6.1.4.1.674.10892.2.4.2.1.12','1.3.6.1.4.1.674.10892.2.4.2.1.4','1.3.6.1.4.1.674.10892.2.4.2.1.5','DELL');");


$dbh->do("INSERT INTO COMPANY_UPS(CURRENT,VOLTAGE,CUR_UPS_LOAD,REM_BATT_CAP,UPS_BATT_RUNTIME,COMPANY) VALUES('.1.3.6.1.4.1.318.1.1.26.8.3.1.5','.1.3.6.1.4.1.318.1.1.1.4.2.1.0','.1.3.6.1.4.1.318.1.1.26.8.3.1.4','.1.3.6.1.4.1.318.1.1.1.2.2.1.0','.1.3.6.1.4.1.318.1.1.1.2.2.3.0','AP88XX');");

$dbh->do("INSERT INTO COMPANY_UPS(CURRENT,VOLTAGE,CUR_UPS_LOAD,REM_BATT_CAP,UPS_BATT_RUNTIME,COMPANY) VALUES('.1.3.6.1.4.1.318.1.1.1.4.2.4.0','.1.3.6.1.4.1.318.1.1.1.4.2.1.0','.1.3.6.1.4.1.318.1.1.1.4.2.3.0','.1.3.6.1.4.1.318.1.1.1.2.2.1.0','.1.3.6.1.4.1.318.1.1.1.2.2.3.0','AP89XX');");

$dbh->do("drop table IF EXISTS COMPANY_PDU");
$dbh->do("CREATE TABLE IF NOT EXISTS COMPANY_PDU(
id int(11) NOT NULL AUTO_INCREMENT,
POWER_WATT tinytext NOT NULL,
POWER_VA tinytext NOT NULL,
PDU_LOAD tinytext NOT NULL,
COMPANY varchar(100) NOT NULL,
PRIMARY KEY(id))ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");

$dbh->do("INSERT INTO COMPANY_PDU(POWER_WATT,POWER_VA,PDU_LOAD,COMPANY) VALUES('.1.3.6.1.4.1.318.1.1.1.4.2.3.0','.1.3.6.1.4.1.318.1.1.1.4.2.3.0','.1.3.6.1.4.1.318.1.1.1.4.2.3.0','APC');");

$dbh->do("INSERT INTO COMPANY_PDU(POWER_WATT,POWER_VA,PDU_LOAD,COMPANY) VALUES('1.3.6.1.4.1.674.10892.2.4.1.1.12','1.3.6.1.4.1.674.10892.2.4.1.1.13','1.3.6.1.4.1.674.10892.2.4.1.1.10','DELL');");

$dbh->do("INSERT INTO COMPANY_PDU(POWER_WATT,POWER_VA,PDU_LOAD,COMPANY) VALUES('.1.3.6.1.4.1.318.1.1.26.4.3.1.5','.1.3.6.1.4.1.318.1.1.26.4.3.1.9','.1.3.6.1.4.1.318.1.1.26.8.3.1.4','AP88XX');");

$dbh->do("INSERT INTO COMPANY_PDU(POWER_WATT,POWER_VA,PDU_LOAD,COMPANY) VALUES('.1.3.6.1.4.1.318.1.1.26.4.3.1.5','.1.3.6.1.4.1.318.1.1.26.4.3.1.9','.1.3.6.1.4.1.318.1.1.26.8.3.1.4','AP89XX');");

$dbh->do("INSERT INTO COMPANY_PDU(POWER_WATT,POWER_VA,PDU_LOAD,COMPANY) VALUES('.1.3.6.1.4.1.318.1.1.26.4.3.1.5','.1.3.6.1.4.1.318.1.1.26.4.3.1.9','.1.3.6.1.4.1.318.1.1.26.8.3.1.4','AP86XX');");


while(1)
{

my $sth = $dbh->prepare("SELECT id,IP,PORT,COMMUNITY FROM UPS");  
$sth->execute();
  while ( my $row = $sth->fetchrow_arrayref() )
  {
my ($id_ups, $IP_ups, $PORT_ups, $COMMUNITY_ups,$DEVICES_ups) = @{$row};
  # print("ups side id $id_ups\n ups side ip $IP_ups\n ups side port $PORT_ups\nups side community $COMMUNITY_ups\n ups side devices$DEVICES_ups\n");
   my $pid=fork();
   if($pid==0)
    {     

$dsn ="DBI:mysql:database=$database;
                    host= $host;
                    port= $port";
$dbh= DBI->connect($dsn, $username, $password, {RaiseError=>1,AutoCommit=>1});       

     if (!-e "ups.$id_ups.rrd") 
          {     
             RRDs::create("ups.$id_ups.rrd",
                "--step",60,
                "DS:power:GAUGE:120:U:U",
                "DS:cur_UpsLoad:GAUGE:120:U:U",
                "DS:rem_batteryCapacity:GAUGE:120:U:U",
                "RRA:AVERAGE:0.5:1:864",
                "RRA:AVERAGE:0.5:6:672",
                "RRA:AVERAGE:0.5:24:744",
                "RRA:AVERAGE:0.5:288:730");
          } 

my $IP_PORT_ups="$IP_ups:$PORT_ups";
 
    my ($session, $error) = Net::SNMP->session(
    -hostname  => "$IP_PORT_ups",
    -community => "$COMMUNITY_ups",
    -maxmsgsize    => 65535,
    );
  
  if (defined($session))
    {
                 my $company_desc = ".1.3.6.1.2.1.1.1.0";
                 my $result = $session->get_request(
                        -varbindlist => [$company_desc],
                         ); 
                 
                my $comp_descr= $result->{$company_desc};
               # print Dumper($comp_descr);
if($comp_descr){

    my $sth_two_ups_col = $dbh->prepare("SELECT * FROM COMPANY_UPS");  
       $sth_two_ups_col->execute();
     my @ups_col_array;
              while (my $row_two_ups_col = $sth_two_ups_col->fetchrow_arrayref() )
                 {
                  my ($a,$b,$c,$d,$e,$f,$ups_comp_col)=@{$row_two_ups_col};
                   # print("ups company side $a,$b,$c,$d,$e,$f,$ups_comp_col\n");
                   push(@ups_col_array,$ups_comp_col);
                  }
           $sth_two_ups_col->finish();
           #  print("@ups_col_array\n"); 
        foreach my $company_name(@ups_col_array)
          {

            if (index($comp_descr, $company_name) != -1)
               {
          my $sth_two = $dbh->prepare("SELECT CURRENT,VOLTAGE,CUR_UPS_LOAD,REM_BATT_CAP,UPS_BATT_RUNTIME FROM COMPANY_UPS WHERE COMPANY_UPS.COMPANY='$company_name'");  
             $sth_two->execute();
              while ( my $row_two_ups = $sth_two->fetchrow_arrayref() )
                 {
        my ($current_utility_ups,$output_voltage_ups,$current_UPS_load,$remaining_battery_capacity,$UPS_battery_runtime)=@{$row_two_ups};
        my @oid_array_ups;
#print("ups oids $current_utility_ups\n$output_voltage_ups\n$current_UPS_load\n$remaining_battery_capacity\n$UPS_battery_runtime\nn");                    
                push(@oid_array_ups, ($current_utility_ups,$output_voltage_ups,$current_UPS_load,$remaining_battery_capacity,$UPS_battery_runtime));
                 response_handler(@oid_array_ups,$id_ups,$IP_ups,$PORT_ups,$COMMUNITY_ups,$DEVICES_ups);
                  }
                $sth_two->finish();
               }

       }
     
     } #($comp_descr)    

   } # (defined($session))
$dbh->disconnect();
exit;
}# if($pid==0)
}# while ( my $row
$sth->finish();



my $sth2 = $dbh->prepare("SELECT id,IP,PORT,COMMUNITY FROM PDU");  
$sth2->execute();
  while ( my $row2 = $sth2->fetchrow_arrayref() )
  {
   my ($id_pdu, $IP_pdu, $PORT_pdu, $COMMUNITY_pdu,$UPS_pdu) = @{$row2};
   
   my $pid=fork();
   if($pid==0)
    {  

$dsn ="DBI:mysql:database=$database;
                    host= $host;
                    port= $port";
$dbh= DBI->connect($dsn, $username, $password, {RaiseError=>1,AutoCommit=>1});  

     if (!-e "pdu.$id_pdu.rrd") 
          {     
             RRDs::create("pdu.$id_pdu.rrd",
                "--step",60,
                "DS:power_watt:GAUGE:120:U:U",
                "DS:power_va:GAUGE:120:U:U",
                "DS:load:GAUGE:120:U:U",
                "RRA:AVERAGE:0.5:1:864",
                "RRA:AVERAGE:0.5:6:672",
                "RRA:AVERAGE:0.5:24:744",
                "RRA:AVERAGE:0.5:288:730");
          } 

my $IP_PORT_pdu="$IP_pdu:$PORT_pdu";
 
    my ($session_pdu, $error) = Net::SNMP->session(
    -hostname  => "$IP_PORT_pdu",
    -community => "$COMMUNITY_pdu",
    -maxmsgsize    => 65535,
    );
   
  if (defined($session_pdu))
    {
           
   my $company_desc_pdu = ".1.3.6.1.2.1.1.1.0";
                 my $result_pdu = $session_pdu->get_request(
                        -varbindlist => [$company_desc_pdu],
                         ); 

          my $comp_descr_pdu= $result_pdu->{$company_desc_pdu};
               # print Dumper($comp_descr_pdu);

if($comp_descr_pdu){

    my $sth_two_pdu_col = $dbh->prepare("SELECT * FROM COMPANY_PDU");  
       $sth_two_pdu_col->execute();
my @pdu_col_array;
              while (my $row_two_pdu_col = $sth_two_pdu_col->fetchrow_arrayref() )
                 {
                  my ($x,$y,$z,$t,$comp_col)=@{$row_two_pdu_col}; 
                  push(@pdu_col_array,$comp_col);
                  }
$sth_two_pdu_col->finish();
#print("@pdu_col_array\n");
        foreach my $name(@pdu_col_array)
          { 
            if (index($comp_descr_pdu, $name) != -1)
               {
                   my $sth_two_pdu = $dbh->prepare("SELECT POWER_WATT,POWER_VA,PDU_LOAD FROM COMPANY_PDU WHERE COMPANY='$name'");  
                      $sth_two_pdu->execute();
              while ( my $row_two_pdu = $sth_two_pdu->fetchrow_arrayref() )
                 {
        my ($power_watt_pdu,$power_va_pdu,$load_pdu)=@{$row_two_pdu};
      
       #print("pdu power watt:$power_watt_pdu\npdu_power_va $power_va_pdu\npdu load $load_pdu\n");
           my @oid_array_pdu;
           push(@oid_array_pdu,($power_watt_pdu,$power_va_pdu,$load_pdu));
          
          response_handler_pdu(@oid_array_pdu,$id_pdu,$IP_pdu,$PORT_pdu,$COMMUNITY_pdu,$UPS_pdu);  

#print("power oid: $power_watt_pdu\n power va:$power_va_pdu\nload $load_pdu\nid $id_pdu\nip: $IP_pdu\nport $PORT_pdu\ncommunity $COMMUNITY_pdu\nups$UPS_pdu\n");

                 }
              $sth_two_pdu->finish();
             
             }
                
          }
     
     } # ($comp_descr_pdu)

   } # (defined($session))
$dbh->disconnect();
exit;
}# if($pid==0)
}# while ( my $ro
$sth2->finish();
sleep(60);
} #while(1)


sub string_extractor
{
my ($string) = @_;
$string =~ s/\#.*//;
 $string =~ tr/",;//d;
$string =~ s/^\s+|\s+$//g;
return $string;
}


sub response_handler
{
    
$dsn ="DBI:mysql:database=$database;
                    host= $host;
                    port= $port";
$dbh= DBI->connect($dsn, $username, $password, {RaiseError=>1,AutoCommit=>1});
 

my($current_utility_ups,$output_voltage_ups,$current_UPS_load,$remaining_battery_capacity,$UPS_battery_runtime,$id,$IP,$PORT,$COMMUNITY,$DEVICES)=@_;

my $IP_PORT="$IP:$PORT";

my $status; 

#print("current:$current_utility_ups\nvoltage:$output_voltage_ups\nload:$current_UPS_load\ncapacity:$remaining_battery_capacity\nruntime$UPS_battery_runtime\nid:$id\nip:$IP\nport:$PORT\ncommunity:$COMMUNITY\ndevices:$DEVICES\n");
    my ($session_two, $error) = Net::SNMP->session(
    -hostname  => "$IP_PORT",
    -community => "$COMMUNITY",
    -maxmsgsize    => 65535,
    );
           my @array;
       
           push(@array,($current_utility_ups,$output_voltage_ups,$current_UPS_load,$remaining_battery_capacity,$UPS_battery_runtime));
    
	   my $result_ups = $session_two->get_request(
                        -varbindlist => [@array],
                         );
          # print Dumper($result_ups);    

           if (!defined $result_ups)
                        {
                          printf "ERROr from response handler UPS: %s.\n", $session_two->error();
                          $session_two->close();
                          exit 1;
                        }

     
       my $cur_util= $result_ups->{$current_utility_ups};
       my $out_vol=$result_ups->{$output_voltage_ups};
       my $cur_ups_load= $result_ups->{$current_UPS_load};
       my $rem_batt_capac=$result_ups->{$remaining_battery_capacity};
       my  $UPS_bat_run=$result_ups->{$UPS_battery_runtime};

       print("current     : $cur_util\n");
       print("voltage     : $out_vol\n");
       print("current load: $cur_ups_load\n");
       print("rem bat capa: $rem_batt_capac\n");
       print("runnint time: $UPS_bat_run\n");

my $power= $cur_util * $out_vol;

my $outfile  = "power.$IP.$PORT.$COMMUNITY.txt"; 
open(my $fhout, '>>', $outfile) or die "Could not open file '$outfile' $!";
my $epoch = time();
my $powerinfo = $epoch.' '.$power;
print $fhout "$powerinfo\n"; 
close $fhout;



#SENDING TRAPS

my $ent_OID = '.1.3.6.1.4.1.41717.10';

#getting battery capacity threshold drop level to send trap
$sth = $dbh->prepare("SELECT WARNING, DANGER, CRITICAL FROM TRAP_MANAGER WHERE id=1;");
$sth->execute() or die $DBI::errstr;
$rows = +$sth->rows;
#print "Number of rows found :" ; print $rows; print "\n";

if($rows == 0){
$sth->finish();
print "no trap manager device configured \n";
}
else{
my @thresholds = $sth->fetchrow_array();
my ($warning_trap, $danger_trap, $critical_trap) = @thresholds;
$sth->finish();
print "warning=$warning_trap, danger=$danger_trap, critical=$critical_trap\n";



if (($rem_batt_capac <= $warning_trap) && ($rem_batt_capac > $danger_trap)) {

$status = 'warning';

my $sth_traptrack = $dbh->prepare("SELECT STATUS FROM TRAP_TRACK WHERE IP = '$IP' AND PORT = '$PORT' AND COMMUNITY = '$COMMUNITY';");
$sth_traptrack->execute() or die $DBI::errstr;
$rows = +$sth_traptrack->rows;

if($rows == 0 ) {
$sth_traptrack->finish();

my $sth_traptrack = $dbh->prepare("INSERT INTO TRAP_TRACK (IP, PORT, COMMUNITY, STATUS ) VALUES ('$IP', '$PORT', '$COMMUNITY','$status' );");
$sth_traptrack->execute() or die $DBI::errstr;
$sth_traptrack->finish();

my $sth_trap = $dbh->prepare("SELECT id, IP, PORT, COMMUNITY from TRAP_MANAGER WHERE id=1;");
$sth_trap->execute() or die $DBI::errstr;
$rows = +$sth_trap->rows;
#print "Number of rows found :" ; print $rows; print "\n";

if($rows == 0){
$sth_trap->finish();
#print "no manager device configured \n";
}
else{
my @manager = $sth_trap->fetchrow_array();
my ($id_trap, $IP_trap, $PORT_trap, $COMMUNITY_trap) = @manager;
$sth_trap->finish();
print "Sending trap to manager IP=$IP_trap, PORT=$PORT_trap, COMMUNITY=$COMMUNITY_trap\n";
#sending danger trap to manager
system("snmptrap -v 1 -c $COMMUNITY_trap $IP_trap:$PORT_trap $ent_OID  $IP 6 247 ' ' $ent_OID.1 s '$PORT\@$COMMUNITY' $ent_OID.2 i '1'");
}

}

else {
my @previous_status = $sth_traptrack->fetchrow_array();
my ($old_status) = @previous_status;
$sth_traptrack->finish();

print "new trap status is : $status and previous trap status is: $old_status from device '$IP:$PORT:$COMMUNITY'\n";

if ($status =! $old_status) {

my $sth_traptrack = $dbh->prepare("UPDATE SET TRAP_TRACK STATUS = '$status' WHERE IP = '$IP' AND PORT = '$PORT' AND COMMUNITY = '$COMMUNITY';");
$sth_traptrack->execute() or die $DBI::errstr;
$sth_traptrack->finish();

my $sth_trap = $dbh->prepare("SELECT id, IP, PORT, COMMUNITY from TRAP_MANAGER WHERE id=1;");
$sth_trap->execute() or die $DBI::errstr;
$rows = +$sth_trap->rows;
#print "Number of rows found :" ; print $rows; print "\n";

if($rows == 0){
$sth_trap->finish();
#print "no manager device configured \n";
}
else{
my @manager = $sth_trap->fetchrow_array();
my ($id_trap, $IP_trap, $PORT_trap, $COMMUNITY_trap) = @manager;
$sth_trap->finish();
print "Sending trap to manager IP=$IP_trap, PORT=$PORT_trap, COMMUNITY=$COMMUNITY_trap\n";
#sending danger trap to manager
system("snmptrap -v 1 -c $COMMUNITY_trap $IP_trap:$PORT_trap $ent_OID  $IP 6 247 ' ' $ent_OID.1 s '$PORT\@$COMMUNITY' $ent_OID.2 i '1'");
}
}
}

}
elsif (($rem_batt_capac <= $danger_trap) && ($rem_batt_capac > $critical_trap)){

$status = 'danger';

my $sth_traptrack = $dbh->prepare("SELECT STATUS FROM TRAP_TRACK WHERE IP = '$IP' AND PORT = '$PORT' AND COMMUNITY = '$COMMUNITY';");
$sth_traptrack->execute() or die $DBI::errstr;
$rows = +$sth_traptrack->rows;

if($rows == 0 ) {
$sth_traptrack->finish();

my $sth_traptrack = $dbh->prepare("INSERT INTO TRAP_TRACK (IP, PORT, COMMUNITY, STATUS ) VALUES ('$IP', '$PORT', '$COMMUNITY','$status' );");
$sth_traptrack->execute() or die $DBI::errstr;
$sth_traptrack->finish();

my $sth_trap = $dbh->prepare("SELECT id, IP, PORT, COMMUNITY from TRAP_MANAGER WHERE id=1;");
$sth_trap->execute() or die $DBI::errstr;
$rows = +$sth_trap->rows;
#print "Number of rows found :" ; print $rows; print "\n";

if($rows == 0){
$sth_trap->finish();
#print "no manager device configured \n";
}
else{
my @manager = $sth_trap->fetchrow_array();
my ($id_trap, $IP_trap, $PORT_trap, $COMMUNITY_trap) = @manager;
$sth_trap->finish();
print "Sending trap to manager IP=$IP_trap, PORT=$PORT_trap, COMMUNITY=$COMMUNITY_trap\n";
#sending danger trap to manager
system("snmptrap -v 1 -c $COMMUNITY_trap $IP_trap:$PORT_trap $ent_OID  $IP 6 247 ' ' $ent_OID.1 s '$PORT\@$COMMUNITY' $ent_OID.2 i '2'");
}

}

else {
my @previous_status = $sth_traptrack->fetchrow_array();
my ($old_status) = @previous_status;
$sth_traptrack->finish();

print "new trap status is : $status and previous trap status is: $old_status from device '$IP:$PORT:$COMMUNITY'\n";

if ($status =! $old_status) {

my $sth_traptrack = $dbh->prepare("UPDATE SET TRAP_TRACK STATUS = '$status' WHERE IP = '$IP' AND PORT = '$PORT' AND COMMUNITY = '$COMMUNITY';");
$sth_traptrack->execute() or die $DBI::errstr;
$sth_traptrack->finish();

my $sth_trap = $dbh->prepare("SELECT id, IP, PORT, COMMUNITY from TRAP_MANAGER WHERE id=1;");
$sth_trap->execute() or die $DBI::errstr;
$rows = +$sth_trap->rows;
#print "Number of rows found :" ; print $rows; print "\n";

if($rows == 0){
$sth_trap->finish();
#print "no manager device configured \n";
}
else{
my @manager = $sth_trap->fetchrow_array();
my ($id_trap, $IP_trap, $PORT_trap, $COMMUNITY_trap) = @manager;
$sth_trap->finish();
print "Sending trap to manager IP=$IP_trap, PORT=$PORT_trap, COMMUNITY=$COMMUNITY_trap\n";
#sending danger trap to manager
system("snmptrap -v 1 -c $COMMUNITY_trap $IP_trap:$PORT_trap $ent_OID  $IP 6 247 ' ' $ent_OID.1 s '$PORT\@$COMMUNITY' $ent_OID.2 i '2'");
}
}
}

}
elsif ($rem_batt_capac <= $critical_trap) {

$status = 'critical';

my $sth_traptrack = $dbh->prepare("SELECT STATUS FROM TRAP_TRACK WHERE IP = '$IP' AND PORT = '$PORT' AND COMMUNITY = '$COMMUNITY';");
$sth_traptrack->execute() or die $DBI::errstr;
$rows = +$sth_traptrack->rows;

if($rows == 0 ) {
$sth_traptrack->finish();

my $sth_traptrack = $dbh->prepare("INSERT INTO TRAP_TRACK (IP, PORT, COMMUNITY, STATUS ) VALUES ('$IP', '$PORT', '$COMMUNITY','$status' );");
$sth_traptrack->execute() or die $DBI::errstr;
$sth_traptrack->finish();

my $sth_trap = $dbh->prepare("SELECT id, IP, PORT, COMMUNITY from TRAP_MANAGER WHERE id=1;");
$sth_trap->execute() or die $DBI::errstr;
$rows = +$sth_trap->rows;
#print "Number of rows found :" ; print $rows; print "\n";

if($rows == 0){
$sth_trap->finish();
#print "no manager device configured \n";
}
else{
my @manager = $sth_trap->fetchrow_array();
my ($id_trap, $IP_trap, $PORT_trap, $COMMUNITY_trap) = @manager;
$sth_trap->finish();
print "Sending trap to manager IP=$IP_trap, PORT=$PORT_trap, COMMUNITY=$COMMUNITY_trap\n";
#sending danger trap to manager
system("snmptrap -v 1 -c $COMMUNITY_trap $IP_trap:$PORT_trap $ent_OID  $IP 6 247 ' ' $ent_OID.1 s '$PORT\@$COMMUNITY' $ent_OID.2 i '3'");
}

}

else {
my @previous_status = $sth_traptrack->fetchrow_array();
my ($old_status) = @previous_status;
$sth_traptrack->finish();

print "new trap status is : $status and previous trap status is: $old_status from device '$IP:$PORT:$COMMUNITY'\n";

if ($status =! $old_status) {

my $sth_traptrack = $dbh->prepare("UPDATE SET TRAP_TRACK STATUS = '$status' WHERE IP = '$IP' AND PORT = '$PORT' AND COMMUNITY = '$COMMUNITY';");
$sth_traptrack->execute() or die $DBI::errstr;
$sth_traptrack->finish();

my $sth_trap = $dbh->prepare("SELECT id, IP, PORT, COMMUNITY from TRAP_MANAGER WHERE id=1;");
$sth_trap->execute() or die $DBI::errstr;
$rows = +$sth_trap->rows;
#print "Number of rows found :" ; print $rows; print "\n";

if($rows == 0){
$sth_trap->finish();
#print "no manager device configured \n";
}
else{
my @manager = $sth_trap->fetchrow_array();
my ($id_trap, $IP_trap, $PORT_trap, $COMMUNITY_trap) = @manager;
$sth_trap->finish();
print "Sending trap to manager IP=$IP_trap, PORT=$PORT_trap, COMMUNITY=$COMMUNITY_trap\n";
#sending danger trap to manager
system("snmptrap -v 1 -c $COMMUNITY_trap $IP_trap:$PORT_trap $ent_OID  $IP 6 247 ' ' $ent_OID.1 s '$PORT\@$COMMUNITY' $ent_OID.2 i '3'");
}
}
}

}
 
}



#SENDING EMAIL

my $sth_email = $dbh->prepare("SELECT * FROM EMAIL");  
$sth_email->execute();
$rows = +$sth_email->rows;
if($rows == 0) {
print "No email receivers cofigured\n";
}

my $to;
my $from = 'Visuallux@gmail.com';
#my $from = 'tkurehwaseka@gmail.com';
my $subject;
my $message;


while (my $row_email = $sth_email->fetchrow_arrayref() )
     
{
        
my ($email_id,$email_name,$email_address,$phone,$warning,$danger,$critical)=@{$row_email};

$to = $email_address;

      
if (($rem_batt_capac <= $warning) && ($rem_batt_capac > $danger)) {

$status = 'warning';

my $sth_emailtrack = $dbh->prepare("SELECT STATUS FROM EMAIL_TRACK WHERE IP = '$IP' AND PORT = '$PORT' AND COMMUNITY = '$COMMUNITY' AND EMAIL = '$email_address';");
$sth_emailtrack->execute() or die $DBI::errstr;
$rows = +$sth_emailtrack->rows;

if($rows == 0 ) {
$sth_emailtrack->finish();

my $sth_emailtrack = $dbh->prepare("INSERT INTO EMAIL_TRACK (IP, PORT, COMMUNITY, EMAIL, STATUS ) VALUES ('$IP', '$PORT', '$COMMUNITY', '$email_address', '$status' );");
$sth_emailtrack->execute() or die $DBI::errstr;
$sth_emailtrack->finish();

$subject = 'CAUTION: UPS Battery Level Notification';
$message = "CAUTION: The battey capacity of UPS with id '$id' has dropped to CAUTION level";

open(MAIL, "|/usr/sbin/sendmail -t");
 
# Email Header
print MAIL "To: $to\n";
print MAIL "From: $from\n";
print MAIL "Subject: $subject\n\n";
# Email Body
print MAIL $message;

close(MAIL);
print "Email Sent Successfully to $to\n";


}

else {
my @previous_status = $sth_emailtrack->fetchrow_array();
my ($old_status) = @previous_status;
$sth_emailtrack->finish();

print "new email status is : $status and previous email status is: $old_status from device '$IP:$PORT:$COMMUNITY' to handler $to\n";

if ($status =! $old_status) {

my $sth_emailtrack = $dbh->prepare("UPDATE SET EMAIL_TRACK STATUS = '$status' WHERE IP = '$IP' AND PORT = '$PORT' AND COMMUNITY = '$COMMUNITY' AND EMAIL = '$email_address';");
$sth_emailtrack->execute() or die $DBI::errstr;
$sth_emailtrack->finish();

$subject = 'CAUTION: UPS Battery Level Notification';
$message = "CAUTION: The battey capacity of UPS with id '$id' has dropped to CAUTION level";

open(MAIL, "|/usr/sbin/sendmail -t");
 
# Email Header
print MAIL "To: $to\n";
print MAIL "From: $from\n";
print MAIL "Subject: $subject\n\n";
# Email Body
print MAIL $message;

close(MAIL);
print "Email Sent Successfully to $to\n";



}
}




}
elsif (($rem_batt_capac <= $danger) && ($rem_batt_capac > $critical)){

$status = 'danger';

my $sth_emailtrack = $dbh->prepare("SELECT STATUS FROM EMAIL_TRACK WHERE IP = '$IP' AND PORT = '$PORT' AND COMMUNITY = '$COMMUNITY' AND EMAIL = '$email_address';");
$sth_emailtrack->execute() or die $DBI::errstr;
$rows = +$sth_emailtrack->rows;

if($rows == 0 ) {
$sth_emailtrack->finish();

my $sth_emailtrack = $dbh->prepare("INSERT INTO EMAIL_TRACK (IP, PORT, COMMUNITY, EMAIL, STATUS ) VALUES ('$IP', '$PORT', '$COMMUNITY', '$email_address', '$status' );");
$sth_emailtrack->execute() or die $DBI::errstr;
$sth_emailtrack->finish();


$subject = 'WARNING: UPS Battery Level Notification';
$message = "WARNING: The battey capacity of UPS with id '$id' has dropped to WARNING level";

open(MAIL, "|/usr/sbin/sendmail -t");
 
# Email Header
print MAIL "To: $to\n";
print MAIL "From: $from\n";
print MAIL "Subject: $subject\n\n";
# Email Body
print MAIL $message;

close(MAIL);
print "Email Sent Successfully to $to\n";


}

else {
my @previous_status = $sth_emailtrack->fetchrow_array();
my ($old_status) = @previous_status;
$sth_emailtrack->finish();

print "new email status is : $status and previous email status is: $old_status from device '$IP:$PORT:$COMMUNITY' to handler $to\n";

if ($status =! $old_status) {

my $sth_emailtrack = $dbh->prepare("UPDATE SET EMAIL_TRACK STATUS = '$status' WHERE IP = '$IP' AND PORT = '$PORT' AND COMMUNITY = '$COMMUNITY' AND EMAIL = '$email_address';");
$sth_emailtrack->execute() or die $DBI::errstr;
$sth_emailtrack->finish();


$subject = 'WARNING: UPS Battery Level Notification';
$message = "WARNING: The battey capacity of UPS with id '$id' has dropped to WARNING level";

open(MAIL, "|/usr/sbin/sendmail -t");
 
# Email Header
print MAIL "To: $to\n";
print MAIL "From: $from\n";
print MAIL "Subject: $subject\n\n";
# Email Body
print MAIL $message;

close(MAIL);
print "Email Sent Successfully to $to\n";



}
}




}
elsif ($rem_batt_capac <= $critical) {


$status = 'critical';

my $sth_emailtrack = $dbh->prepare("SELECT STATUS FROM EMAIL_TRACK WHERE IP = '$IP' AND PORT = '$PORT' AND COMMUNITY = '$COMMUNITY' AND EMAIL = '$email_address';");
$sth_emailtrack->execute() or die $DBI::errstr;
$rows = +$sth_emailtrack->rows;

if($rows == 0 ) {
$sth_emailtrack->finish();

my $sth_emailtrack = $dbh->prepare("INSERT INTO EMAIL_TRACK (IP, PORT, COMMUNITY, EMAIL, STATUS ) VALUES ('$IP', '$PORT', '$COMMUNITY', '$email_address', '$status' );");
$sth_emailtrack->execute() or die $DBI::errstr;
$sth_emailtrack->finish();


$message = "DANGER: The battey capacity of UPS with id '$id' has dropped to CRITICAL level";
$subject = 'DANGER: UPS Battery Level Notification';
open(MAIL, "|/usr/sbin/sendmail -t");
 
# Email Header
print MAIL "To: $to\n";
print MAIL "From: $from\n";
print MAIL "Subject: $subject\n\n";
# Email Body
print MAIL $message;

close(MAIL);
print "Email Sent Successfully to $to\n";


}

else {
my @previous_status = $sth_emailtrack->fetchrow_array();
my ($old_status) = @previous_status;
$sth_emailtrack->finish();

print "new email status is : $status and previous email status is: $old_status from device '$IP:$PORT:$COMMUNITY' to handler $to\n";

if ($status =! $old_status) {

my $sth_emailtrack = $dbh->prepare("UPDATE SET EMAIL_TRACK STATUS = '$status' WHERE IP = '$IP' AND PORT = '$PORT' AND COMMUNITY = '$COMMUNITY' AND EMAIL = '$email_address';");
$sth_emailtrack->execute() or die $DBI::errstr;
$sth_emailtrack->finish();


$message = "DANGER: The battey capacity of UPS with id '$id' has dropped to CRITICAL level";
$subject = 'DANGER: UPS Battery Level Notification';
open(MAIL, "|/usr/sbin/sendmail -t");
 
# Email Header
print MAIL "To: $to\n";
print MAIL "From: $from\n";
print MAIL "Subject: $subject\n\n";
# Email Body
print MAIL $message;

close(MAIL);
print "Email Sent Successfully to $to\n";



}
}


}

}
$sth_email->finish();




$dbh->do("INSERT INTO UPS(id,IP,PORT,COMMUNITY, POWER,CUR_UPS_LOAD,REM_BATT_CAP,UPS_BATT_RUNTIME) 
      VALUES('$id','$IP',$PORT,'$COMMUNITY',$power,$cur_ups_load,$rem_batt_capac,'$UPS_bat_run') 
      ON DUPLICATE KEY UPDATE POWER=$power,CUR_UPS_LOAD=$cur_ups_load,REM_BATT_CAP=$rem_batt_capac,UPS_BATT_RUNTIME='$UPS_bat_run'");

    RRDs::update("ups.$id.rrd", "N:". "$power:"."$cur_ups_load:"."$rem_batt_capac");
    my $Err = RRDs::error;
    die "Error while updating: $Err\n" if $Err;  

 }

sub response_handler_pdu

{

my($power_watt_pdu,$power_va_pdu,$load_pdu,$id_pdu,$IP_pdu,$PORT_pdu,$COMMUNITY_pdu,$UPS_pdu)=@_;
 my $IP_PORT_pdu="$IP_pdu:$PORT_pdu";
 
#print("$power_watt_pdu\n$power_va_pdu\n$load_pdu\nid:$id_pdu\nip:$IP_pdu\nport:$PORT_pdu\ncommunity:$COMMUNITY_pdu\n");
    my ($session_pdu_two, $error) = Net::SNMP->session(
    -hostname  => "$IP_PORT_pdu",
    -community => "$COMMUNITY_pdu",
    -maxmsgsize    => 65535,
    );
           my @array_pdu;
           push(@array_pdu,($power_watt_pdu,$power_va_pdu,$load_pdu));

            # print("pdu oid @array_pdu\n");
	   my $result_pdu_two = $session_pdu_two->get_request(
                        -varbindlist => [@array_pdu],
                         );

           if (!defined $result_pdu_two)
                        {
                          printf "ERROR: %s.\n", $session_pdu_two->error();
                          $session_pdu_two->close();
                          exit 1;
                        }

     
       my $power_watt_pdu_value= $result_pdu_two->{$power_watt_pdu};
       my $power_va_pdu_value=$result_pdu_two->{$power_va_pdu};
       my $load_pdu_value=$result_pdu_two->{$load_pdu};

        print("power in watt  : $power_watt_pdu_value\n");
        print("power in va    : $power_va_pdu_value\n");
        print("load           : $load_pdu_value\n");

my $outfile  = "power.$IP_pdu.$PORT_pdu.$COMMUNITY_pdu.txt"; 
open(my $fhout, '>>', $outfile) or die "Could not open file '$outfile' $!";
my $epoch = time();
my $powerinfo = $epoch.' '.$power_watt_pdu_value;
print $fhout "$powerinfo\n"; 
close $fhout;


      #print Dumper($result_pdu_two);

$dbh->do("INSERT INTO PDU(id,IP,PORT,COMMUNITY,POWER_WATT,POWER_VA,PDU_LOAD) 
      VALUES('$id_pdu','$IP_pdu',$PORT_pdu,'$COMMUNITY_pdu',$power_watt_pdu_value,$power_va_pdu_value,$load_pdu_value) 
      ON DUPLICATE KEY UPDATE POWER_WATT=$power_watt_pdu_value,POWER_VA=$power_va_pdu_value,PDU_LOAD=$load_pdu_value");

    RRDs::update("pdu.$id_pdu.rrd", "N:"."$power_watt_pdu_value:"."$power_va_pdu_value:"."$load_pdu_value");
    my $Err = RRDs::error;
    die "Error while updating: $Err\n" if $Err;
                                    

}





