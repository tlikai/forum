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
            array('name', 'unique', 'className' => 'Tag'),

            array('name', 'match', 'pattern' => '/^\w+$/'),
            array('title', 'match', 'pattern' => '/^[a-zA-Z0-9\x{4e00}-\x{9fa5}_\- ]+$/u'),
        );
    }
}
