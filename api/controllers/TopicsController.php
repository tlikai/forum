<?php

class TopicsController extends ApiController
{
    public function accessRules()
    {
        return array_merge(array(
            array('allow',
                'actions' => array('like', 'unlike', 'follow', 'unfollow'),
                'users' => array('@'),
            ),
        ), parent::accessRules());
    }

    public function actionIndex()
    {
        Response::make(Topic::model()->paginate());
    }

    public function actionShow($id)
    {
        Response::make(Topic::model()->with('user')->findOrFail($id));
    }

    public function actionCreate()
    {
        $topic = new Topic('create');
        $topic->attributes = Input::only('subject', 'content');
        $topic->tagIds = Input::get('tags');

        if (!$topic->save()) {
            throw new ValidationException($topic->getErrors());
        }

        Response::make($topic);
    }

    public function actionUpdate($id)
    {
        $topic = Topic::model()->findOrFail($id);
        $topic->attributes = Input::only('subject', 'content');
        $topic->tagIds = Input::get('tags');

        if ($topic->created_by != Yii::app()->user->id) {
            throw new UnauthorizedException;
        }

        if (!$topic->save()) {
            throw new ValidationException($topic->getErrors());
        }

        Response::make($topic);
    }

    public function actionDelete($id)
    {
        $topic = Topic::model()->findOrFail($id);

        if ($topic->created_by != Yii::app()->user->id) {
            throw new UnauthorizedException;
        }

        if (!$topic->delete()) {
            throw new RuntimeException;
        }

        Response::make($topic);
    }

    public function actionLike($id)
    {
        if (!TopicFlag::like($id)) {
            throw new RuntimeException;
        }
    }

    public function actionUnlike($id)
    {
        if (!TopicFlag::unlike($id)) {
            throw new RuntimeException;
        }
    }
}
