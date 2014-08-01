#!/bin/bash

if ! [ -f /home/itesm-student/projects/compass/back/mutex/cron_mutex.file ]
then
  touch /home/itesm-student/projects/compass/back/mutex/cron_mutex.file
  /usr/local/php/bin/php /home/itesm-student/projects/compass/back/cron.php processing
  rm /home/itesm-student/projects/compass/back/mutex/cron_mutex.file
fi
