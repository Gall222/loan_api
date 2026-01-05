<?php

return [
    'bootstrap' => [
        'queue',
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

    'components' => [
        'db' => [
            'class' => yii\db\Connection::class,
            'dsn' => sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                getenv('DB_HOST') ?: 'db',
                getenv('DB_PORT') ?: 5432,
                getenv('DB_DATABASE') ?: 'loans'
            ),
            'username' => getenv('DB_USERNAME') ?: 'user',
            'password' => getenv('DB_PASSWORD') ?: 'password',
            'charset' => 'utf8',
        ],

        'cache' => [
            'class' => yii\caching\FileCache::class,
        ],

        'redis' => [
            'class' => yii\redis\Connection::class,
            'hostname' => getenv('REDIS_HOST') ?: 'redis',
            'port' => getenv('REDIS_PORT') ?: 6379,
            'database' => getenv('REDIS_DB') ?: 0,
        ],

        'queue' => [
            'class' => yii\queue\redis\Queue::class,
            'redis' => 'redis',
            'channel' => getenv('QUEUE_CHANNEL') ?: 'queue',
        ],
    ],
];
