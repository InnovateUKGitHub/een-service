#!/bin/bash
####################################
#
# Symlinked to /etc/cron.daily during build
#
####################################

cd /home/web/een-service/cron/ && ./merlin-sync.sh $APPLICATION_ENV