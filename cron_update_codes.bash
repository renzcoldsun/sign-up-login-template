#!/bin/bash

DATETIME=$(date '+%F')
LOGFILE="/var/log/bvz_cron_update-${DATETIME}"
RUNFILE="/var/tmp/CRON_UPDATE_RUNNING"

if [ -f "${RUNFILE}" ]
then
    exit 0
fi
touch ${RUNFILE}
exec >> ${LOGFILE} 2>&1
[[ -f "/var/.xxx_idfile" ]] && pushd /var/www/html
git clean -f
git reset --hard HEAD
git pull origin master

# cleanup
find /var/log -name 'bvz_cron_update' -mtime +3 -exec rm {} \;

# good quit
rm -f ${RUNFILE}
exit 0