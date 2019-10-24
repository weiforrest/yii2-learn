<?php

namespace backend\models;

use Exception;
use Yii;

class User extends \common\models\User
{
    public $repassword;

    const SCENARIO_REGISTER = 'register';
    const SCENARIO_UPDATE = 'update';


    public function rules()
    {
        return [
            [['username', 'password','repassword'], 'string'],
            [['username', 'email'], 'unique'],
            ['email', 'email'],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            [['username','email','password','repassword'], 'required', 'on' => [self::SCENARIO_REGISTER]],
            [['username', 'email'], 'required', 'on'=> [self::SCENARIO_UPDATE]],

        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->generateAuthKey();
            $this->setPassword($this->password);
        }else{
            if( !empty($this->password) && empty($this->repassword) ){
                $this->addError("repassword", Yii::t('yii', '{attribute} must be equal to "{compareValueOrAttribute}".', [
                    'attribute' => yii::t('app', 'Repeat Password'),
                    'compareValueOrAttribute' => yii::t('app', 'Password')
                    ])
                );
                return false;
            }
            $this->setPassword( $this->password );
        }
        return parent::beforeSave($insert);
    }


}