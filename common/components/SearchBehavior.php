<?php

class SearchBehavior extends CActiveRecordBehavior
{
    public $searchAttribute;

    public function getType()
    {
        return strtolower(get_class($this->owner));
    }

    public function getId()
    {
        return $this->owner->primaryKey;
    }

    public function getTitle()
    {
        return $this->owner->{$this->searchAttribute};
    }

	public function afterSave($event)
	{
        return Indexer::add($this->type, $this->id, $this->title);
	}

    public function search($query, $limit = 10)
    {
        $ids = Indexer::search($this->type, $query);
        if (!$ids) {
            return $this->owner;
        }

        $this->owner->dbCriteria->addInCondition('t.' . $this->owner->tableSchema->primaryKey, $ids);

        return $this->owner;
    }
}
