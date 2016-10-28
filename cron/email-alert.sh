#!/bin/bash

# Send email alert to user - spike temporary for Methods development
# Replace CONTACT by the id of a contact in salesforce
# And repeat the line for as many user as you want
/usr/bin/php ../public/index.php email-alert --user=CONTACT

