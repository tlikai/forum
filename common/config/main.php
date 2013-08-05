<?php

return array(
    'name' => 'application name',

    'preload' => array('log'),

    'aliases' => array(
        'common' => __DIR__ . '/../../common',
        'vendor' => __DIR__ . '/../../common/vendors',
        'console' => __DIR__ . '/../../console',
        'backend' => __DIR__ . '/../../backend',
        'frontend' => __DIR__ . '/../../frontend',
    ),

    'import' => array(
        'common.models.*',
        'common.helpers.*',
        'common.components.*',

        'application.models.*',
        'application.helpers.*',
        'application.components.*',
        'application.controllers.*',
    ),

    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=forum_with_yii',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => '',
        ),

        'redis' => array(
            'class' => 'common.extensions.YiiRedis.ARedisConnection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => true,
            'prefix' => 'Yii.redis.'
        ),

        'user' => array(
            'class' => 'WebUser',
        ),

        'log' => array(
            'class'  => 'CLogRouter',
            'routes' => array(
                /*
                array(
                    'class' => 'CWebLogRoute',
                    'levels' => 'error, warning',
                ),
                 */
            ),
        ),
    ),

    // application parameters
    'params' => array(),
);
