<?php

namespace app\models;

use Yii;
use yii\base\Model; // foto
use yii\web\UploadedFile; // foto

/**
 * This is the model class for table "{{%adv}}".
 *
 * @property integer $id
 * @property string $title
 * @property double $price
 * @property string $image
 * @property string $date_public
 * @property string $description
 * @property string $address
 * @property string $phone_number
 * @property string $email
 * @property string $id_category
 * @property string $creator
 */

class Adv extends \yii\db\ActiveRecord
{
    public $file; //foto

    //foto
    public function upload()
    {
        if ($this->validate()) {
            $this->file->saveAs('uploads/' . $this->file->baseName . '.' . $this->file->extension);
            //или вот здесь реализовать метод, который сохраняет имя файла в базу                        
            return true;
        } else {
            return false;
        }
    }

    //задает дату создания объявления
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->date_public = date("Y-m-d H:i:s");
            $this->creator = Yii::$app->user->id;
            return true;
        } else {
            return false;
        }
    }

    //relations
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'id_category']);
    }
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'creator']);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%adv}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'price', 'phone_number', 'id_category', 'description'], 'required'],
            [['price'], 'number'],
            [['date_public'], 'safe'],
            [['description', 'address', 'foto'], 'string'],
            [['id_category', 'creator'], 'integer'],
            [['title', 'image', 'phone_number', 'email'], 'string', 'max' => 255],
            [['file'], 'safe'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'], //foto
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id объявления',
            'title' => 'Заголовок',
            'price' => 'Цена',
            'image' => 'картинка',
            'date_public' => 'Дата размещения',
            'description' => 'Описание',
            'address' => 'Адрес',
            'phone_number' => 'Контактный номер телефона',
            'email' => 'Email',
            'id_category' => 'id категории',
            'creator' => 'создатель объявления',
            'foto' => 'Изображение', //foto
            'file' => 'Загрузить изображение', //foto
        ];
    }

    /**
     * @inheritdoc
     * @return AdvQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdvQuery(get_called_class());
    }
}
