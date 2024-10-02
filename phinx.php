<?php

return
  [
    'paths' => [
      'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
      'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
      'default_migration_table' => 'phinxlog',
      'default_environment' => 'development',
      'production' => [
        'adapter' => $_ENV["DB_PROD_DRIVER"] ?? 'mysql',
        'host' => $_ENV["DB_PROD_HOST"] ?? 'localhost',
        'name' => $_ENV["DB_PROD_NAME"] ?? 'production_db',
        'user' => $_ENV["DB_PROD_USER"] ?? 'root',
        'pass' => $_ENV["DB_PROD_PASS"] ?? '',
        'port' => $_ENV["DB_PROD_PORT"] ?? '3306',
        'charset' => 'utf8',
      ],
      'development' => [
        'adapter' => $_ENV["DB_DEV_DRIVER"] ?? 'mysql',
        'host' => $_ENV["DB_DEV_HOST"] ?? 'localhost',
        'name' => $_ENV["DB_DEV_NAME"] ?? 'production_db',
        'user' => $_ENV["DB_DEV_USER"] ?? 'root',
        'pass' => $_ENV["DB_DEV_PASS"] ?? '',
        'port' => $_ENV["DB_DEV_PORT"] ?? '3306',
        'charset' => 'utf8',
      ],
      'testing' => [
        'adapter' => $_ENV["DB_TEST_DRIVER"] ?? 'mysql',
        'host' => $_ENV["DB_TEST_HOST"] ?? 'localhost',
        'name' => $_ENV["DB_TEST_NAME"] ?? 'production_db',
        'user' => $_ENV["DB_TEST_USER"] ?? 'root',
        'pass' => $_ENV["DB_TEST_PASS"] ?? '',
        'port' => $_ENV["DB_TEST_PORT"] ?? '3306',
        'charset' => 'utf8',
      ]
    ],
    'version_order' => 'creation'
  ];
