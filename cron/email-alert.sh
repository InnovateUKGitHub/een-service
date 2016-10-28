#!/bin/bash

# Send email alert to user - spike temporary for Methods development
# Replace CONTACT by the id of a contact in salesforce
# And repeat the line for as many user as you want
/usr/bin/php ../public/index.php email-alert --user=0038E00000RnHs4
/usr/bin/php ../public/index.php email-alert --user=0038E00000RnHu0

