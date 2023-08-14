#!/bin/bash

set -e

composer install --no-cache --prefer-dist --no-scripts --no-progress

npm run dev
