# Setup guide
## Install packages
composer i --ignore-platform-reqs

## Build up docker container
./vendor/bin/sail build --no-cache && ./vendor/bin/sail up -d

## Run migrations with seeder
./vendor/bin/sail artisan migrate --seed

## Run migrations with seeder
./vendor/bin/sail artisan migrate --seed

# Import csv 
Send POST request to this endpoint 'api/import'. Body content file -> (csv import file)

