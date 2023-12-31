## Orders delay notification system

This project is written using the Laravel framework and docker. and has 3 web services.

The Postman file for requests along with all possible responses is available at the root of the project.

After cloning the project, run the following commands in the root of the project to start the process:

```php
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

Then :

```php
cp .env.example .env
```

And after that:

```php
./vendor/bin/sail up -d
```

You might need a VPN or changing DNS to be able to get docker images.

Next with this command you can go inside the container:

```php
./vendor/bin/sail shell
```

Run the following command when you are inside:

```php
php artisan key:generate
```

Until here these were standard commands for running the laravel project on docker(using sail),
after these run this command:

```php
php artisan setup
```

Every time you run this command, it will rerun the migrations and add some basic
necessary records in the following tables to make it easier to test. you will see a list of records
inserted in the database after running the command.

```php
tables:
    * vendors
    * users
    * agents
    * couriers
    * orders
```

Half of the inserted orders passed their time for delivery, and you can request a delay, and half of them still have
time to process and deliver.

You can run tests inside container with this command:

```php
php artisan test
```

Or with this command outside the container:

```php
./vendor/bin/sail php artisan test
```

### Test Scenarios:

Since we only have 3 APIs, some of the scenarios can not be seen just after running ```php artisan setup``` command
and need other APIs or database manual manipulation.

for those, there are separate commands to create related records in the database:

| Scenario                           |     API      |    Command    |     Response      |
|------------------------------------|:------------:|:-------------:|:-----------------:|
| get new time delivery              | report-delay | time_delivery | order_id, user_id |
| allocate a delay queue to an agent |   allocate   |  delay_queue  |     agent_id      |

These command will return necessary params or body values for you to request.

#### Example:  ```php artisan time_delivery```
