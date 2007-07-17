#!/bin/bash

# php ./runcronjobs.php -dall exportcsv;
php ./runcronjobs.php -dall exportcsv | tee var/log/exportcsv.log

