# Set Cron
crontab -e

# Add this to the file: 
0 * * * * php /var/www/html/index.php HourlyEvents generatehourlyevents

# may need to set permissions for this file
sudo chmod 777 ./cron_logs.txt
