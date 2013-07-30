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

    public function relations()
    {
        return array(
            'tag' => array(self::BELONGS_TO, 'Tag', 'tag_id'),
            'topic' => array(self::BELONGS_TO, 'Topic', 'topic_id'),
        );
    }
}
