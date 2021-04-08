FROM    centos:7

# To start, run yum update to get the latest version, then use yum itself to install all the software we need
RUN yum -y --setopt tsflags=nodocs update && \
yum -y --setopt tsflags=nodocs install gcc httpd openssl openssl-devel perl perl-App-cpanminus \ 
mod_ssl glibc.i686 perl-CGI perl-DBI perl-Carp perl-Data-Dumper libxml2-devel php php-mysqli \
mysql screen curl vim "perl(DBD::mysql)" && \
yum clean all 

# We just installed perl-App-cpanminus in the line above, lets use it to install something, then delete the .cpanm folder that it will make as it's not required
RUN cpanm --install JSON --force; rm -fr root/.cpanm; exit 0

# Expose ports as you need
EXPOSE 80
EXPOSE 8080/tcp
EXPOSE 443/tcp
EXPOSE 444/tcp

# Add the application files
ADD my_files/applications /applications

# Delete the default httpd.conf and copy in our own instead
RUN rm -fr /etc/httpd/conf/httpd.conf
ADD my_files/httpd.conf /etc/httpd/conf/httpd.conf

# Setting permissions on files (not required for this particular example but were for Perl scripts in another - line left in as a reminder) 
RUN chmod -R 755 /applications/my-apps 
RUN chmod -R 755 /applications/my-apps/matt/db.cgi 

# Copy our run-httpd.sh script into the build, and set permissions on it
ADD my_files/run-httpd.sh /run-httpd.sh
RUN chmod -v +rx /run-httpd.sh

# Run our run-httpd.sh to start the server.
CMD ["/run-httpd.sh"]
