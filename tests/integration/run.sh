#!/bin/bash
dir=$(cd "$(dirname "$0")";pwd);

cd ${dir}
php ./../../vendor/bin/phpunit $* .

# Запуск:
# docker-compose run php ./tests/integration/run.sh
# docker-compose run php ./tests/integration/run.sh --filter testCpsResponsesRight