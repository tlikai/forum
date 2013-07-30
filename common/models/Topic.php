<?php

class Topic extends ActiveRecord
{
    public $tagIds;

    public function tableName()
    {
        return '{{topics}}';
    }

    public function rules()
    {
        return array(
            array('subject, content', 'required'),
            array('tagIds', 'required', 'on' => 'create'),
            array('created_by, last_post_at, last_post_by, score, like_count, reply_count, follow_count, created_at, updated_at', 'numerical'),
        );
    }

    public function relations()
    {
        return array(
            'user' => array(static::BELONGS_TO, 'User', 'created_by'),
        );
    }

    public function init()
    {
        parent::init();

        $this->onBeforeSave = function(CEvent $e){
            if ($this->isNewRecord) {
                if (!$this->created_by) {
                    $this->created_by = Yii::app()->user->id;    
                }
                $this->last_post_at = $this->created_at;
                $this->last_post_by = $this->created_by;
            }
        };
        $this->onAfterSave = array($this, 'resolveTags');
    }

    public function resolveTags(CEvent $e)
    {
        if (!$this->isNewRecord) {
            TopicTag::model()->deleteAll('topic_id = ?', array($this->id));
        }

        $tagIds = array_slice($this->tagIds, 0, 5);
        foreach ($tagIds as $tagId) {
            $model = new TopicTag;
            $model->attributes = array(
                'tag_id' => $tagId,
                'topic_id' => $this->id,
            );
            $model->save();
        }
    }
}
