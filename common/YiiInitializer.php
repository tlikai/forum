<?php

require_once __DIR__ . '/vendors/autoload.php';
require_once __DIR__ . '/vendors/yiisoft/yii/framework/yii.php';

class YiiInitializer
{
    public static function createApplication($folder, $type = 'web')
    {

        $appPath = __DIR__ . '/../' . $folder;

        $config = CMap::mergeArray(
            require_once __DIR__ . '/config/main.php',
            require_once $appPath . '/config/main.php'
        );

        if ($type == 'console') {
            $class = 'CConsoleApplication';
        } else {
            require_once __DIR__ . '/components/WebApplication.php';
            $class = 'WebApplication';
        }

        return Yii::createApplication($class, $config);
    }
}
