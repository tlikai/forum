<?php

class Reply extends ActiveRecord
{
    public function tableName()
    {
        return '{{replies}}';
    }

    public function rules()
    {
        return array(
            array('topic_id, content', 'required'), 
            array('created_by, like_count, created_at, updated_at', 'numerical'),
        );
    }

    public function init()
    {
        parent::init();

        $this->onBeforeSave = function(CEvent $e) {
            if ($this->isNewRecord) {
                if (!$this->created_by) {
                    $this->created_by = Yii::app()->user->id;    
                }
            }
        };

        $this->onAfterSave = function(CEvent $e) {
            if ($this->isNewRecord) {
                TopicFlag::reply($this->topic_id, $this->created_by);
            }
        };

        $this->onAfterDelete = function(CEvent $e) {
            TopicFlag::reply($this->topic_id, $this->created_by);
        };
    }

    public function scopeTopic($id)
    {
        $this->dbCriteria->compare('topic_id', $id);
        return $this;
    }
}
