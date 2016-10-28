#!/bin/bash
####################################
#
# Symlinked to /etc/cron.daily during build
#
####################################

cd /home/web/een-service/cron/ && ./merlin-sync.sh $APPLICATION_ENV
cd /home/web/een-service/cron/ && ./event-sync.sh $APPLICATION_ENV
cd /home/web/een-service/cron/ && ./email-alert.sh $APPLICATION_ENV
