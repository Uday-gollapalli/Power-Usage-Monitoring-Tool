ABOUT TIS FOLDER:
*******************

This folder contains files of Applied Network Management project work of developing a power consumption monitoring tool. 
The files in this folder make a solution of the project work and they include all the necessary codes, SRS, installation manual, 
user manual documents for this software, and db.conf which contains the database login credentials

IMPORTANT NOTE: 
***************

All the files must be kept in this folder
The db.conf file contains the database login credentials, this file can be replace by another file with same structure
Read this file carefully and follow the instructions steps in this file.
Read the installation manual and execute the the steps which are not mentioned in this file
Follow the user manual to execute the operations on this software


REQUIREMENTS FOR SOLUTIONS:
***************************

Server operating System:
*************************

standard Ubuntu Linux 14.04 LTS system
(sudo apt-get update)
(sudo apt-get upgrade) 
(sudo apt-get dist-upgrade) 
(sudo apt-get autoremove)


Softwares and tools prerequisites 
**********************************

LAMP server-APACHE,MYSQL,PHP (sudo apt-get install lamp-server^)
RRDtool (sudo apt-get install rrdtool libpango1.0-dev libxml2-dev)
SNMP/snmpd (sudo apt-get install snmp snmpd)
Apache web server (sudo apt-get install apache2)

System Configurations
*********************

    ********** setting apache target folder to /var/www ******** 

type: sudo nano /etc/apache2/sites-available/000-default.conf
change: DocumentRoot /var/www/html to DocumentRoot /var/www
Restart apache after making the change (sudo /etc/init.d/apache2 restart)

    ********** giving user read/write permission in target folder *****

sudo usermod -a -G www-data <some_user>
sudo chgrp -R www-data /var/www
sudo chmod 777 /var/www -R


Build-essentials and modules to be installed
********************************************

   ******** php/frontend module *********

(sudo apt-get install php5 libapache2-mod-php5 php5-mcrypt php5-gd php5-mysql php5-curl php5-cli php5-cgi php5-dev php5-rrd)
(sudo /etc/init.d/apache2 restart)
(sudo apt-get install phpmyadmin apache2 apache2-utils)
(sudo nano /etc/apache2/apache2.conf)
 Add the phpmyadmin config to the file.
 Include /etc/phpmyadmin/apache.conf
 Restart apache:
(sudo service apache2 restart)

   ********mysql/database module**********

(sudo apt-get install mysql-server libapache2-mod-auth-mysql php5-mysql)


   ******* perl/backend module *********

RRD module (sudo apt-get install rrdtool)
RRDs (sudo apt-get install librrds-perl)
RRD::Simple (sudo cpan RRD::Simple)
LWP::Simple (sudo cpan LWP::Simple)
DBI module (sudo cpan DBI)
Net::SNMP module (sudo cpan Net::SNMP)
DBD::mysql module (sudo cpan DBD::mysql)
Net::SNMPTrapd (sudo cpan Net::SNMPTrapd)
Net::SNMP module (sudo cpan Net::SNMP)
Net::SNMP::Interfaces (sudo cpan Net::SNMP::Interfaces)
Mail::Sendmail (sudo cpan Mail::Sendmail)

To install the above perl modules, run the script modules.sh using the command: bash modules.sh


Installation and running
*************************

-Place this folder in /var/www
-Run the backend as root using the command # bash backend.sh or perl backend.pl
-From a web browser of the local server or any device connected to ther server access the frontend by typing in your browser the url: <Server-IP>/Visuallux
-Login with the username = admin and password = admin

