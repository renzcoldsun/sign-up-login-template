#!/bin/bash

DATETIME=$(date '+%F-%H-%M')
LOGFILE="/var/log/bvz_cron_update-${DATETIME}"


exec >> ${LOGFILE} 2>&1
[[ -f "/var/.xxx_idfile" ]] && pushd /var/www/html
git clean -f
git reset --hard HEAD
git pull origin master

# cleanup
find /var/log -name 'bvz_cron_update' -mtime +3 -exec rm {} \;

# good quit
exit 0