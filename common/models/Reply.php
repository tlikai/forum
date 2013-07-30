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
                UserAction::reply($this->created_by, $this->topic_id, $this->id);
            }
        };

        $this->onAfterDelete = function(CEvent $e) {
            UserAction::unReply($this->created_by, $this->topic_id, $this->id);
            UserAction::deleteReply($this->id);
        };
    }

    public function scopeTopic($id)
    {
        $this->dbCriteria->compare('topic_id', $id);
        return $this;
    }
}
