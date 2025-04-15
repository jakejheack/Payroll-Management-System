#!/bin/bash
#
BKP_USER="root"     # Enter the username for backup
BKP_PASS=""        # Enter the password of the backup user
#
BKP_DEST="D:\" # Enter the Backup directory,change this if you have someother location
#
BKP_DAYS="2" # Enter how many days backup you want,
#
MYSQL_HOST="localhost"  
#
#
BKP_DATE="$(date +"%d-%m-%Y-%H:%M:%S-%a")";
#
IGNORE_DB="information_schema mysql performance_schema"
#
[ ! -d $BKP_DEST ] && mkdir -p $BKP_DEST || :
#
MYSQL="$(which mysql)"
MYSQLDUMP="$(which mysqldump)"
GZIP="$(which gzip)"
#
#
DB_LIST="$($MYSQL -u $BKP_USER -h $MYSQL_HOST -p$BKP_PASS -Bse 'show databases')"
#
for db in $DB_LIST
do
    skipdb=-1
    if [ "$IGNORE_DB" != "" ];
    then
for i in $IGNORE_DB
do
   [ "$db" == "$i" ] && skipdb=1 || :
done
    fi
 
    if [ "$skipdb" == "-1" ] ; then
BKP_FILENAME="$BKP_DEST/$db.$BKP_DATE.gz"
#
#
        $MYSQLDUMP -u $BKP_USER -h $MYSQL_HOST -p$BKP_PASS $db | $GZIP -9 > $BKP_FILENAME
    fi
done
#
find $BKP_DEST -type f -mtime +$BKP_DAYS -delete
#