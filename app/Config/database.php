<?php

declare(strict_types=1);

return [

    'host' => getenv('MYSQL_HOST') ?: 'mysql',

    'dbname' => getenv('MYSQL_DATABASE') ?: 'cyberblog',

    'username' => getenv('MYSQL_USER') ?: 'cyberblog_app',

    'password' => getenv('MYSQL_PASSWORD') ?: '',

    'charset' => 'utf8mb4',

];