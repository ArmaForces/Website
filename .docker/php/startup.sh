#!/bin/bash

set -e

bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
