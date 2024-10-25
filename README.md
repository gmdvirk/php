docker exec -it php-db_replica-1 mysql -u root -proot
docker exec -it php-db-1 mysql -u root -proot
-- Connect to the master server

CREATE USER 'replication_user'@'%' IDENTIFIED WITH 'mysql_native_password' BY 'replication_password';

-- Grant replication privileges to the user
GRANT REPLICATION SLAVE ON *.* TO 'replication_user'@'%';

-- Apply the changes
FLUSH PRIVILEGES;

SHOW MASTER STATUS;
STOP SLAVE;

CHANGE REPLICATION SOURCE TO
    SOURCE_HOST='db',  -- The master container name or IP address
    SOURCE_USER='replication_user',  -- Replication user
    SOURCE_PASSWORD='replication_password',  -- Password for replication user
    SOURCE_LOG_FILE='mysql-bin.000003',  -- The log file from master
    SOURCE_LOG_POS=157;  -- The log position from master
START SLAVE;
SHOW SLAVE STATUS\G;
SELECT * FROM events LIMIT 10;
ALTER USER 'sonu'@'%' IDENTIFIED WITH mysql_native_password BY '90avq*9QD#0K';
FLUSH PRIVILEGES;
