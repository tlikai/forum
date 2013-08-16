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
                'actions' => array('avatar', 'signout', 'create', 'update', 'delete'),
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

        if (!$user->save()) {
            throw new ValidationException($user->getErrors());
        }

        Response::make($user);
    }

    public function actionSignin()
    {
        $name = Input::get('name');
        $password = Input::get('password');

        $identity = new UserIdentity($name, $password);
        if (!$identity->authenticate()) {
            throw new InvalidRequestException('Invalid ID or password');
        }
        $duration = true ? 3600 * 24 * 30 : 0; // TODO remember me
        Yii::app()->user->login($identity, $duration);

        Response::make($identity->user);
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

    public function actionUpdate($id)
    {
        $user = User::model()->findOrFail($id);

        // change password
        if (Input::has('password')) {
            $oldScenario = $user->scenario;
            $user->scenario = 'updatePassword';
            $user->attributes = Input::only('password', 'newPassword', 'confirmPassword');
            if (!$user->validate()) {
                throw new ValidationException($user->getErrors());
            }

            $user->password = $user->newPassword;
            if (!$user->save()) {
                throw new RuntimeException;
            }

            $user->scenario = $oldScenario;
        }

        // TODO change other info

        Response::make($user);
    }

    public function actionAvatar()
    {
        Yii::import('common.extensions.file.Upload');
        $upload = new Upload('avatar');
        $upload->setOptions(Yii::app()->params['upload']);
        if (!$upload->save()) {
            throw new ValidationException($upload->errorMessage);
        }

        $user = User::model()->findOrFail(Yii::app()->user->id);
        $user->avatar = $upload->fullPath;
        if (!$user->save()) {
            throw new RuntimeException;
        }

        Response::make($user);
    }
}
