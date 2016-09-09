#!/bin/bash

# Import the event
/usr/bin/php ../public/index.php import --index=event

# Delete non existent event
/usr/bin/php ../public/index.php delete --index=event

