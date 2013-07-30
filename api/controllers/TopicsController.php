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
        $topic = Topic::model()->with('user', 'lastPoster')->findOrFail($id);
        $topic->extraAttributes = array(
            'actions' => array(
                'edit' => $topic->created_by == Yii::app()->user->id,
                'like' => !Yii::app()->user->isGuest && !TopicFlag::hasLike($topic->id, Yii::app()->user->id),
                'follow' => !Yii::app()->user->isGuest && !TopicFlag::hasFollow($topic->id, Yii::app()->user->id),
                'repliy' => !Yii::app()->user->isGuest,
            ),
        );

        Response::make($topic);
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

    public function actionFollow($id)
    {
        if (!TopicFlag::follow($id)) {
            throw new RuntimeException;
        }
    }

    public function actionUnfollow($id)
    {
        if (!TopicFlag::unFollow($id)) {
            throw new RuntimeException;
        }
    }
}
