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
            'class' => 'UrlManager',
			'urlFormat' => 'path',
            'showScriptName' => false,
            'resources' => array(
                'tags',
                'topics',
                'topics.replies',
                array('users', 'only' => array('show', 'update')),
            ),
            'rules' => array(
                // users services
                array('users/<action>', 'pattern' => '<action:(signup|signin|signout)>', 'verb' => 'POST'),
                array('users/<action>', 'pattern' => 'users/<action:(avatar)>', 'verb' => 'POST'),

                // topics services
                array('topics/<action>', 'pattern' => 'topics/<id:\d+>/<action:(like|unlike|follow|unfollow)>', 'verb' => 'POST'),

                // replies services
                array('replies/<action>', 'pattern' => 'topics/<relation_id:\d+>/replies/<id:\d+>/<action:(like|unlike|bookmark|unbookmark)>', 'verb' => 'POST'),
            ),
        ),

        'user' => array(
            'allowAutoLogin' => true,
        ),

        'errorHandler' => array(),
    ),
);
