<?php

Yii::$classMap = array(
    'NotFoundException' => '../components/exceptions.php',
    'UnauthorizedException' => '../components/exceptions.php',
    'InvalidRequestException' => '../components/exceptions.php',
    'ValidationException' => '../components/exceptions.php',
);

return array(
    'basePath' => realpath(__DIR__ . '/../'),

    // application behaviors
    'behaviors' => array(),

    // controllers mappings
    'controllerMap' => array(),

    // application modules
    'modules' => array(),

    // application components
    'components' => array(
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                array('<controller>/index', 'pattern' => '<controller:\w+>', 'verb' => 'GET'),
                array('<controller>/show', 'pattern' => '<controller:\w+>/<id:\d+>', 'verb' => 'GET'),
                array('<controller>/create', 'pattern' => '<controller:\w+>', 'verb' => 'POST'),
                array('<controller>/update', 'pattern' => '<controller:\w+>/<id:\d+>', 'verb' => 'PUT'),
                array('<controller>/delete', 'pattern' => '<controller:\w+>/<id:\d+>', 'verb' => 'DELETE'),
            ),
        ),

        'user' => array(
            'allowAutoLogin' => true,
        ),

        'errorHandler' => array(),
    ),
);
