#!/bin/bash
# Backup of the PHP site and its SQLite database
SITE=/var/www/html/mysite
DB=/var/www/data/notes.db
DEST=/backup
DATE=$(date +%F_%H%M)
LOG=/var/log/backup-site.log

mkdir -p $DEST

# Safe copy of the database, even while the site is in use
sqlite3 $DB ".backup '$DEST/notes_$DATE.db'"

# Archive the site files
tar -czf $DEST/site_$DATE.tar.gz -C $SITE .

# Delete backups older than 7 days
find $DEST -type f -mtime +7 -delete

echo "$(date '+%F %T') Backup OK: notes_$DATE.db, site_$DATE.tar.gz" >> $LOG

# Copy backups to backup01 (off-site)
if rsync -a /backup/ samir@192.168.0.140:/backups/web01/; then
    echo "$(date '+%F %T') Off-site copy OK -> backup01" >> $LOG
else
    echo "$(date '+%F %T') Off-site copy FAILED -> backup01" >> $LOG
fi
