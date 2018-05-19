#!/bin/bash

# HOST appserver.alunos.di.fc.ul.pt
HOST="10.101.151.25"

echo 'Cleaning up...'
rm -rf dist
mkdir dist

echo 'Preparing files...'
cp -R controllers dist
cp -R data_collector dist
cp -R database dist
cp -R includes dist
cp -R models dist
cp -R public_html dist
cp -R private dist
cp -R scripts dist
cp -R tools dist
cp -R utils dist
cp -R views dist
cp -R webservices dist
cp deploy/public_html/x.htaccess dist/public_html/.htaccess
cp .htaccess dist

echo 'Patching files...'
# TODO private.php ?
sed -i 's/localhost\/aw007/appserver\.alunos\.di\.fc\.ul\.pt\/\~aw007/g' dist/public_html/templates/head.html

echo 'Starting deploy...'
sshpass -f "private/appserver.pw" rsync -a --stats dist/ aw007@${HOST}:~/
