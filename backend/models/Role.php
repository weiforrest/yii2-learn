<?php
namespace  backend\models;

use Yii;
use yii\base\Model;

class Role extends  Model
{
    public $name;
    public $description;
    public $rule;
    public $data;

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app','Name'),
            'description' => Yii::t('app', 'Description'),
            'rule' => Yii::t('app', 'Rule'),
            'data' => Yii::t('app', 'Data'),
        ];
    }

    public function rules()
    {
        return [
            [['name', 'description','rule','data'], 'string'],
            [['name'], 'unique'],
            [['name', 'description'], 'required'],
        ];
    }

    
}
