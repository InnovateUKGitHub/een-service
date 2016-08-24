#!/bin/sh
####################################
#
# Install PHP dependencies via Composer
# 
####################################

# exit on error
set -e

# force compile on first build
test -e compiled/drupal/composer.json || forceCompile=true

composerChanges=`diff composer.json compiled/composer.json || true`

if [ ! -z "$composerChanges" ] || [ ! -z "$forceCompile" ];then
    echo "composer.json has changed:"
    echo $composerChanges

    php ./bin/composer self-update

    echo "running composer (with dev packages)"
    php ./bin/composer install --optimize-autoloader

else
    echo "drupal/composer.json has not changed, only running autoload"

    php ./bin/composer self-update
    php ./bin/composer dump-autoload
fi

