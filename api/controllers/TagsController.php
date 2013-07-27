<?php

class TagsController extends ApiController
{
    public function actionIndex()
    {
        Response::make(Tag::model()->paginate());
    }

    public function actionShow($id)
    {
        Response::make(Tag::model()->findOrFail($id));
    }

    public function actionCreate()
    {
        $tag = new Tag('create');
        $tag->attributes = Input::only('name', 'title');

        if (!$tag->save()) {
            throw new ValidationException($tag->getErrors());
        }

        Response::make($tag);
    }

    public function actionUpdate($id)
    {
        $tag = Tag::model()->findOrFail($id);
        $tag->attributes = Input::only('name', 'title');

        if (!$tag->save()) {
            throw new ValidationException($tag->getErrors());
        }

        Response::make($tag);
    }

    public function actionDelete($id)
    {
        $tag = Tag::model()->findOrFail($id);
        if (!$tag->delete()) {
            throw RuntimeException;
        }

        Response::make($tag);
    }
}
