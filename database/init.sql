-- Create a read-only user with access from any host
CREATE USER 'readonly'@'%' IDENTIFIED BY '90avq*9QD#0K';
GRANT SELECT ON ci_badge.* TO 'readonly'@'%';

-- Create a write-access user restricted to localhost only, with specified password
CREATE USER 'writer'@'localhost' IDENTIFIED BY '90avq*9QD#0K';
GRANT ALL PRIVILEGES ON ci_badge.* TO 'writer'@'localhost';

-- Revoke all privileges for other users to write to the ci_badge database
REVOKE INSERT, UPDATE, DELETE ON ci_badge.* FROM 'readonly'@'%';

-- Flush privileges to apply changes
FLUSH PRIVILEGES;
