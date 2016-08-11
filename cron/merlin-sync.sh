#!/bin/bash

# Import the last 12 month one by one
/usr/bin/php ../public/index.php import --month=1
/usr/bin/php ../public/index.php import --month=2
/usr/bin/php ../public/index.php import --month=3
/usr/bin/php ../public/index.php import --month=4
/usr/bin/php ../public/index.php import --month=5
/usr/bin/php ../public/index.php import --month=6
/usr/bin/php ../public/index.php import --month=7
/usr/bin/php ../public/index.php import --month=8
/usr/bin/php ../public/index.php import --month=9
/usr/bin/php ../public/index.php import --month=10
/usr/bin/php ../public/index.php import --month=11
/usr/bin/php ../public/index.php import --month=12

# Delete too old data and delete profile
/usr/bin/php ../public/index.php delete

