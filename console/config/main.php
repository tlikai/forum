<?php

return array(
    'basePath' => realpath(__DIR__ . '/..'),

    'commandMap' => array(
        'migrate' => array(
            'class' => 'system.cli.commands.MigrateCommand',
            'migrationPath' => 'application.migrations'
        ),
    ),
);
