#!/usr/bin/perl -w
use strict;
use warnings;
use DBI;
use Data::Dumper;

print "Content-type:text/html\n\n";

print "<h1>This simple test runs the following SQL:</h1>";
print "<h4>DESC customer</h4>";
print "<p>It also uses the database connection params stored in environment variables to connect to a database in a separate docker container.</p>";

my $db_username = $ENV{'MATT_ENV_DB_USER'};
my $db_password = $ENV{'MATT_ENV_DB_PASSWORD'};
my $db_host_ip =  $ENV{'MATT_ENV_DB_ADDRESS'};
my $db_name =     $ENV{'MATT_ENV_DB_NAME'};
my $db_port =     $ENV{'MATT_ENV_DB_PORT'};

print "<p> -&gt; Connecting to database $db_name on $db_host_ip:$db_port using $db_username and $db_password...</p>";

my $dbh = DBI->connect("dbi:mysql:$db_name;host=$db_host_ip; port=$db_port",$db_username,$db_password) or die "Connection Error: $DBI::errstr\n";

my $sql_query = "DESC customer";
my $sth = $dbh->prepare($sql_query);
$sth->execute or die "SQL Error: $DBI::errstr\n";

print "The results of your DESC query are below:<br />";
print "<textarea rows='20' cols='100'>";
while (my $data = $sth->fetchrow_hashref){
        print Dumper $data;
}

print "</textarea><br /><br />";
print "\n\n Script Completed.";

exit;

__DATA__

my $db_username = $ENV{'MATT_ENV_DB_USER'};
my $db_password = $ENV{'MATT_ENV_DB_PASSWORD'};
my $db_host_ip =  $ENV{'MATT_ENV_DB_ADDRESS'};
my $db_name =     $ENV{'MATT_ENV_DB_NAME'};
my $db_port =     $ENV{'MATT_ENV_DB_PORT'};

print "Connecting to database $db_name on $db_host_ip:$db_port using $db_username and $db_password";

my $dbh = DBI->connect("dbi:mysql:$db_name;host=$db_host_ip; port=$db_port",$db_username,$db_password) or die "Connection Error: $DBI::errstr\n";

my $sql_query = "DESC customer";
my $sth = $dbh->prepare($sql_query);
$sth->execute or die "SQL Error: $DBI::errstr\n";

while (my $data = $sth->fetchrow_hashref){
        print Dumper $data;
}

print "\n\n Script Completed.";
