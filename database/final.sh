#!/bin/bash

# === Configuration ===
OLD_DB_USER="onlyread"
OLD_DB_PASS="}x{3S75DWQq6#"
OLD_DB_NAME="ci_badge"

# Backup file name
BACKUP_FILE="teastdatabase.sql"  # Save as teastdatabase.sql in the current directory
LOG_FILE="$HOME/mysql_backups/backup_restore.log"  # Log file in the specified directory

# Create necessary directories for log file
mkdir -p "$HOME/mysql_backups"
touch "$LOG_FILE"

# Logging function
log() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') [INFO] $1" | tee -a "$LOG_FILE"
}

error_log() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') [ERROR] $1" | tee -a "$LOG_FILE"
}

# === Functions ===

# Test MySQL connection
test_mysql_connection() {
    log "Testing MySQL connection..."
    
    mysql -u${OLD_DB_USER} -p${OLD_DB_PASS} -e "SHOW DATABASES;" 2>> "$LOG_FILE"
    if [ $? -ne 0 ]; then
        error_log "MySQL connection test failed. Please check the credentials and try again."
        exit 1
    fi
    
    log "MySQL connection successful."
}

# Dump the database
dump_database() {
    log "Starting database dump from the server..."

    # Command to dump the database without single-transaction and no-flush privileges
    mysqldump_command="mysqldump -u${OLD_DB_USER} -p${OLD_DB_PASS} \
        --skip-lock-tables --no-tablespaces --no-create-db \
        ci_badge"

    log "Running mysqldump: $mysqldump_command"
    
    # Execute the dump and log error output
    if $mysqldump_command > "$BACKUP_FILE" 2>> "$LOG_FILE"; then
        log "Database dump completed successfully and saved as ${BACKUP_FILE}."
    else
        error_log "Database dump failed. Check the logs for more details."
        # Output the last few lines of the log for quick diagnosis
        tail -n 10 "$LOG_FILE"
        exit 1
    fi
}

# === Main Execution ===

log "=== MySQL Backup Script Started ==="
test_mysql_connection
dump_database
log "=== MySQL Backup Script Completed ==="
