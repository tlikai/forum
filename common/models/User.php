<?php

class User extends ActiveRecord
{
    protected $hidden = array('password');

    public function tableName()
    {
        return '{{users}}';
    }

    public function rules()
    {
        return array(
            array('name, email, password', 'required'),
			array('name', 'length', 'min' => 2, 'max' => 20),
            array('email', 'email'),
            array('password', 'length', 'min' => 6),
            array('follower_count, following_count, created_at, updated_at', 'numerical'),
            array('avatar', 'safe'),

            array('name, email', 'unique', 'className' => 'User', 'on' => 'create'),
        );
    }

    public function init()
    {
        parent::init();

        $this->onBeforeSave = function(CEvent $e) {
            $this->password = Hash::make($this->password);
        };
    }

    public function findByName($name)
    {
        return $this->findByAttributes(array('name' => $name));
    }
}
