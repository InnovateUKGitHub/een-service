#!/bin/bash
####################################
#
# PHPUnit tests
# 
####################################

#rm -rf $workspace/test/PHPUnit/reports
$htdocs/vendor/bin/phpunit -d zend.enable_gc=0
