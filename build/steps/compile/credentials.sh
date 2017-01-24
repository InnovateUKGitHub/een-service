#!/bin/bash

####################################
#
# Insert secrets replacements
#
####################################

#exit on error
set -e 

cd $htdocs/config/autoload

eventbriteglobal=$htdocs/config/autoload/event-brite.global.php
govdelivery=$htdocs/config/autoload/gov-delivery.global.php
merlinglobal=$htdocs/config/autoload/merlin.global.php
salesforceglobal=$htdocs/config/autoload/salesforce.global.php

# Event Brite
sed -i -e "s/%%EVENT_BRITE_SECRET%%/$eventbritesecret/g" $eventbriteglobal
sed -i -e "s/%%EVENT_BRITE_TOKEN%%/$eventbritetoken/g" $eventbriteglobal
sed -i -e "s/%%EVENT_BRITE_PATH%%/$eventbritepath/g" $eventbriteglobal
# Gov Delivery
sed -i -e "s/%%GOV_DELIVERY_TOKEN%%/$govdeliverytoken/g" $govdelivery
# Merlin Global
sed -i -e "s/%%MERLIN_GLOBAL_USERNAME%%/$merlinglobalusername/g" $merlinglobal
sed -i -e "s/%%MERLIN_GLOBAL_PASSWORD%%/$merlinglobalpassword/g" $merlinglobal
# Salesforce
sed -i -e "s/%%SALESFORCE_GLOBAL_USERNAME%%/$salesforceusername/g" $salesforceglobal
sed -i -e "s/%%SALESFORCE_GLOBAL_PASSWORD%%/$salesforcepassword/g" $salesforceglobal
sed -i -e "s/%%SALESFORCE_GLOBAL_TOKEN%%/$salesforcetoken/g" $salesforceglobal
sed -i -e "s/%%SALESFORCE_GLOBAL_NAMESPACE%%/$salesforcenamespace/g" $salesforceglobal 

