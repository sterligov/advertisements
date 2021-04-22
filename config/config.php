<?php

return [
    'doctrine.dbparams' => [
        'driver' => 'pdo_mysql',
        'user' => 'advertising_user',
        'password' => 'advertising_pass',
        'dbname' => 'advertising',
        'host' => 'advertising_db',
    ],
    'doctrine.entityPath' => [__DIR__ . '/../src/Domain/Entity'],
    'doctrine.isDevMode' => true,
    'doctrine.migrations' => [
        'table_storage' => [
            'table_name' => 'doctrine_migration_versions',
            'version_column_name' => 'version',
            'version_column_length' => 1024,
            'executed_at_column_name' => 'executed_at',
            'execution_time_column_name' => 'execution_time',
        ],

        'migrations_paths' => [
            'App\Migrations' => __DIR__ . '/../src/Migrations',
        ],

        'all_or_nothing' => true,
        'check_database_platform' => true,
        'organize_migrations' => 'none',
    ],

    'cors.methods' => ['POST', 'GET', 'PUT', 'DELETE'],
    'cors.host' => 'localhost',
    'cors.scheme' => 'http',
    'cors.port' => 80,

    'jwt.secret' => 'secretkey',
    'jwt.ttl' => 24 * 60 * 60,
    'jwt.alg' => 'HS256',
    'jwt.ignore' => [
        '/v1/sign-up',
        '/v1/sign-in',
        '/v1/health',
        '/v1/metrics'
    ],
    'jwt.attribute' => 'user',

    'json.depth' => 64,
    'json.associative' => true,

    'app.mode' => 'dev',
    'router.cache' => __DIR__ . '/../var/router.cache',

    'log.path' => __DIR__ . '/../var/app.log',
    'log.name' => 'app',

    'redis.host' => 'advertising_redis',
    'redis.port' => 6379,
];
