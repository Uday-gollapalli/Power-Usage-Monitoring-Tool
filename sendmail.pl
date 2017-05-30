#!/usr/bin/perl

my $id = 2;
 

my $to = 'tklogbaman@gmail.com';
my $from = 'tkurehwaseka@gmail.com';
my $subject = 'UPS Battery Level Notification';
my $message;
my $batt_capacity = 20;

if (($batt_capacity <= 50) && ($batt_capacity > 30)) {
$message = "CUATION: The battey capacity of UPS with id '$id' has dropped to NORMAL level";
}
elsif (($batt_capacity <= 30) && ($batt_capacity > 10)){
$message = "WARNING: The battey capacity of UPS with id '$id' has dropped to WARNING level";
}
elsif ($batt_capacity <= 10) {
$message = "DANGER: The battey capacity of UPS with id '$id' has dropped to CRITICAL level";
}
 
open(MAIL, "|/usr/sbin/sendmail -t");
 
# Email Header
print MAIL "To: $to\n";
print MAIL "From: $from\n";
print MAIL "Subject: $subject\n\n";
# Email Body
print MAIL $message;

close(MAIL);
print "Email Sent Successfully\n"
