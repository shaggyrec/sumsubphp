#!/bin/bash
dir=$(cd "$(dirname "$0")";pwd);

cd ${dir}/unit
php ../../vendor/bin/phpunit . &&
cd ${dir}/integration &&
php ../../vendor/bin/phpunit $* .
