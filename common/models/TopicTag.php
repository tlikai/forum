<?php

class TopicTag extends ActiveRecord
{
    protected $timestamp = false;

    public function tableName()
    {
        return '{{topic_tags}}';
    }

    public function rules()
    {
        return array(
            array('tag_id, topic_id', 'required'),
        );
    }
}
