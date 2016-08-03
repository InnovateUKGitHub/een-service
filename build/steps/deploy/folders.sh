#!/bin/bash
####################################
#
# Create required directories, extract base resources, set folder permissions
#
####################################

# create required directories
test -e $htdocs || mkdir $htdocs
test -e $htdocs/cache || mkdir $htdocs/cache
test -e $htdocs/logs || mkdir $htdocs/logs

# create log file
touch $htdocs/logs/error.log

# change directory permissions
chmod 777 $htdocs/cache -R
chmod 777 $htdocs/logs -R
