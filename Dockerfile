# Use an official MySQL runtime as a parent image
FROM mysql:8.0

# Set the root password
ENV MYSQL_ROOT_PASSWORD=r00t

# Create a new database and user
ENV MYSQL_DATABASE=analuiza
ENV MYSQL_USER=neto
ENV MYSQL_PASSWORD=r00t

# Copy a custom MySQL configuration file
#COPY my.cnf /etc/mysql/conf.d/my.cnf
