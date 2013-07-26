<?php

class ApiController extends CController
{
    public function init()
    {
        parent::init();
        Yii::app()->onError = array($this, 'errorHandler');
        Yii::app()->onException = array($this, 'errorHandler');
    }

    public function filters()
    {
        return array('accessControl');
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'show'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('create', 'update', 'delete'),
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function errorHandler(CEvent $event)
    {
		$event->handled = true;
        if ($event instanceof CExceptionEvent) {
            $data['code'] = $event->exception->getCode();
            if ($event->exception instanceof ValidationException) {
                $data['message'] = $event->exception->getMessages(); 
            } else {
                $data['message'] = $event->exception->getMessage(); 
            }
        } else {
			$data['code'] = 500;
			$data['message'] = $event->message; 
        }

        if (YII_DEBUG) {
            $data['traceback'] = debug_backtrace();
        }

        $data['code'] = $data['code'] ?: 500;
        Response::make($data, $data['code']);
        Yii::app()->end();
    }
}
