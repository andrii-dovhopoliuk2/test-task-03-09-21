<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => $_ENV['DB_DSN_TEMP'],
    'username' => $_ENV['DB_USER_TEMP'],
    'password' => $_ENV['DB_PASSWORD_TEMP'],
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
