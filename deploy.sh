#!/bin/bash

DEPLOY_USERNAME="sifekano"
DEPLOY_PATH="/home/sifekano/www/bzrn.ch"
SERVER_NAME="sl2224.web.hostpoint.ch"

rsync --progress -avu --delete "app/" $DEPLOY_USERNAME@$SERVER_NAME:$DEPLOY_PATH/app/
rsync --progress -avu --delete "public/" $DEPLOY_USERNAME@$SERVER_NAME:$DEPLOY_PATH/public/
rsync --progress -avu --delete "ressources/" $DEPLOY_USERNAME@$SERVER_NAME:$DEPLOY_PATH/ressources/
rsync --progress -avu --delete "vendor/" $DEPLOY_USERNAME@$SERVER_NAME:$DEPLOY_PATH/vendor/
rsync --progress -avu "writable/" $DEPLOY_USERNAME@$SERVER_NAME:$DEPLOY_PATH/writable/
rsync --progress -avu ".env.example" $DEPLOY_USERNAME@$SERVER_NAME:$DEPLOY_PATH
ssh $DEPLOY_USERNAME@$SERVER_NAME "rm -rf $DEPLOY_PATH/writable/cache/twig/*"
ssh $DEPLOY_USERNAME@$SERVER_NAME "chmod -R 755 $DEPLOY_PATH"
