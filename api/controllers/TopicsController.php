<?php

class TopicsController extends ApiController
{
    public function actionIndex()
    {
        Response::make(Topic::model()->paginate());
    }

    public function actionShow($id)
    {
        Response::make(Topic::model()->findOrFail($id));
    }

    public function actionCreate()
    {
        // TODO implement topic tags
        $topic = new Topic('create');
        $topic->attributes = Input::only('subject', 'content', 'tags');

        if (!$topic->save()) {
            throw new ValidationException($topic->getErrors());
        }

        Response::make($topic);
    }

    public function actionUpdate($id)
    {
        $topic = Topic::model()->findOrFail($id);
        $topic->attributes = Input::only('subject', 'content', 'tags');

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
}
