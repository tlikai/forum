<?php

class Tag extends ActiveRecord
{
    protected $timestamp = false;

    public function tableName()
    {
        return '{{tags}}';
    }

    public function rules()
    {
        return array(
            array('name, title', 'required'),
			array('name, title', 'length', 'min' => 2, 'max' => 20),
        );
    }
}
