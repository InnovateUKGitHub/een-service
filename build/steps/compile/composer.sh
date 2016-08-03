#!/bin/sh
####################################
#
# Install PHP dependencies via Composer
# 
####################################

# exit on error
set -e

# force compile on first build
test -e compiled/drupal/composer.lock || forceCompile=true

composerChanges=`diff composer.lock compiled/composer.lock || true`

if [ ! -z "$composerChanges" ] || [ ! -z "$forceCompile" ];then
    echo "composer.lock has changed:"
    echo $composerChanges

    php $htdocs/bin/composer self-update

    if [ "$phpdox" = "true" ] || [ "$testcucumber" = "true" ] || [ "$testphpunit" = "true" ]; then
        echo "running composer (with dev packages)"
        php $htdocs/bin/composer install --optimize-autoloader
    else
        echo "running composer (no dev packages)"
        php $htdocs/bin/composer install --no-dev --optimize-autoloader
    fi

else
    echo "drupal/composer.lock has not changed, only running autoload"

    php $htdocs/bin/composer dump-autoload
fi

