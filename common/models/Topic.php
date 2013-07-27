<?php

class Topic extends ActiveRecord
{
    public $tags;

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
    }

    public function tableName()
    {
        return '{{topics}}';
    }

    public function rules()
    {
        return array(
            array('subject, content, tags', 'required'),
            array('created_by, last_post_at, last_post_by, score, like_count, reply_count, follower_count, created_at, updated_at', 'numerical'),
        );
    }
}
