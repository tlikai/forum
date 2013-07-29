<?php

class RepliesController extends ApiController
{
    public function actionIndex($relation_id)
    {
        $replies = new Reply;
        Response::make($replies->scopeTopic($relation_id)->paginate());
    }

    public function actionShow($relation_id, $id)
    {
        $reply = Reply::model()->scopeTopic($relation_id)->findOrFail($id);
        Response::make($reply);
    }

    public function actionCreate($relation_id)
    {
        $reply = new Reply();
        $reply->topic_id = $relation_id;
        $reply->attributes = Input::only('content');

        if (!$reply->save()) {
            throw new ValidationException($reply->getErrors());
        }

        Response::make($reply);
    }

    public function actionUpdate($relation_id, $id)
    {
        $reply = Reply::model()->scopeTopic($relation_id)->findOrFail($id);
        $reply->attributes = Input::only('content');

        if ($reply->created_by != Yii::app()->user->id) {
            throw new UnauthorizedException;
        }

        if (!$reply->save()) {
            throw new ValidationException($reply->getErrors());
        }

        Response::make($reply);
    }

    public function actionDelete($relation_id, $id)
    {
        $reply = Reply::model()->scopeTopic($relation_id)->findOrFail($id);

        if ($reply->created_by != Yii::app()->user->id) {
            throw new UnauthorizedException;
        }

        if (!$reply->delete()) {
            throw new RuntimeException;
        }

        Response::make($reply);
    }
}
