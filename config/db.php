<?php

return [
    //для локалки
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=my-red-promo;',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',

    //для docker-compose
    // class' => 'yii\db\Connection',
    // 'dsn' => 'mysql:host=db;dbname=my-red-promo;',
    // 'username' => 'root',
    // 'password' => 'root',
    // 'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
