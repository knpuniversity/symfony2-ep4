#!/bin/sh

DEPLOY_USER=knpevents
DEPLOY_HOST=knpevents.com
DEPLOY_PORT=22123
DEPLOY_DIR=/var/www/knpevents.com/deploy/

rsync --archive --force --delete --progress --compress --checksum --exclude-from=app/config/rsync_exclude.txt -e "ssh -p $DEPLOY_PORT" ./ $DEPLOY_USER@$DEPLOY_HOST:$DEPLOY_DIR
ssh -p $DEPLOY_PORT $DEPLOY_USER@$DEPLOY_HOST "cd $DEPLOY_DIR && \
export SYMFONY_ENV=prod && \
rm -rf app/cache/$SYMFONY_ENV/* && \
php app/console --env=prod --symlink assets:install web" && \
/usr/bin/php app/console cache:clear --env=prod
/usr/bin/php app/console assetic:dump --env=prod