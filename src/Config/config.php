<?php

declare(strict_types=1);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cloud-filer');
define('BASE_URI', '/cloud-filer');
define('JWT_SECRET', 'example_key');
define('ACCESS_TOKEN_EXP', 2 * 60 * 60 * 1000);
define('REFRESH_TOKEN_EXP', 10 * 24 * 60 * 60 * 1000);