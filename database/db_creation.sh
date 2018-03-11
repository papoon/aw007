#! /bin/bash

newDb='aw007'
newUser='aw007'
newPassword='aw007'
host=localhost

commands="CREATE DATABASE \`${newDb}\`;CREATE USER '${newUser}'@'${host}' IDENTIFIED BY '${newPassword}';GRANT USAGE ON *.* TO '${newUser}'@'${host}' IDENTIFIED BY '${newPassword}';GRANT ALL privileges ON \`${newDb}\`.*
TO '${newUser}'@'${host}';FLUSH PRIVILEGES;"

echo "${commands}" | /usr/bin/mysql -u root -p

