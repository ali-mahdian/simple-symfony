<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env.test');
}

passthru(sprintf(
    'php "%s/../bin/console" -env=%s doctrine:database:drop --force', __DIR__, $_ENV['APP_ENV']
));

passthru(sprintf(
    'php "%s/../bin/console" -env=%s doctrine:database:create',  __DIR__, $_ENV['APP_ENV']
));

passthru(sprintf(
    'php "%s/../bin/console" -env=%s doctrine:schema:create', __DIR__, $_ENV['APP_ENV']
));