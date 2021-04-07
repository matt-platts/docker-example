FROM    centos:7

RUN yum -y --setopt tsflags=nodocs update && \
yum -y --setopt tsflags=nodocs install gcc httpd openssl openssl-devel \ 
perl perl-App-cpanminus mod_ssl glibc.i686 perl-CGI libxml2-devel php \
screen curl vim "perl(DBD::mysql)" && \
yum clean all 

RUN cpanm --install JSON --force; rm -fr root/.cpanm; exit 0

EXPOSE 80
EXPOSE 8080/tcp
EXPOSE 443/tcp
EXPOSE 444/tcp

ADD my_files/applications /applications
RUN rm -fr /etc/httpd/conf/httpd.conf
ADD my_files/httpd.conf /etc/httpd/conf/httpd.conf

# Permissions not being set properly on Jenkins, so force them here:
RUN chmod -R 755 /applications/my-apps 

# Modify /etc/rsyslog.conf to point to the proper server
ADD my_files/run-httpd.sh /run-httpd.sh
RUN chmod -v +rx /run-httpd.sh

CMD ["/run-httpd.sh"]
