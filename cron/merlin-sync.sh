#!/bin/bash

# Import the last 12 month one by one
php ../public/index.php import --month=1
php ../public/index.php import --month=2
php ../public/index.php import --month=3
php ../public/index.php import --month=4
php ../public/index.php import --month=5
php ../public/index.php import --month=6
php ../public/index.php import --month=7
php ../public/index.php import --month=8
php ../public/index.php import --month=9
php ../public/index.php import --month=10
php ../public/index.php import --month=11
php ../public/index.php import --month=12

# Delete too old data and delete profile
php ../public/index.php delete

