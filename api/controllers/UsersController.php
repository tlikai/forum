<?php

class UsersController extends ApiController
{
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'show'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('signup', 'signin'),
                'users' => array('?'),
            ),
            array('allow',
                'actions' => array('signout', 'create', 'update', 'delete'),
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionSignup()
    {
        $user = new User('create');
        $user->setAttributes(Input::only('name', 'email', 'password'));

        if ($user->validate()) {
            if ($user->save()) {
                Response::make($user);
            }
        }

        throw new ValidationException($user->getErrors());
    }

    public function actionSignin()
    {
        $name = Input::get('name');
        $password = Input::get('password');

        $identity = new UserIdentity($name, $password);
        if ($identity->authenticate()) {
			$duration = true ? 3600 * 24 * 30 : 0; // FIXME
            Yii::app()->user->login($identity, $duration);
            Response::make($identity->user);
        }

        throw new InvalidRequestException('Invalid ID or password');
    }

    public function actionSignout()
    {
        Yii::app()->user->logout();
    }

    public function actionShow($id)
    {
        $user = User::model()->findOrFail($id);
        Response::make($user);
    }
}
