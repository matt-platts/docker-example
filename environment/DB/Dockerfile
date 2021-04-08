FROM mysql

# Add a database
ENV MYSQL_DATABASE matt_db 

# Add the content of the sql-scripts/ directory to your image
# All scripts in docker-entrypoint-initdb.d/ are automatically
# executed during container startup
COPY ./schema_matt.sql /docker-entrypoint-initdb.d/
RUN chmod -v uog+rwx /docker-entrypoint-initdb.d/schema_matt.sql

