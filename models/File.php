<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $type
 * @property string $size
 * @property string $description
 * @property string $date_entered
 * @property string $date_updated
 *
 * @property User $user
 * @property PageHasFile[] $pageHasFiles
 * @property Page[] $pages
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file';
    }

    public  function validateFilterType($attr, $params)
    {
        $allowed = array('application/pdf', 'application/msword');

        if (!in_array($this->attr, $allowed)){
            $this->addRrror($attr, 'You cam only upload PDF files or World docs.');
        }
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'type', 'size'], 'required'],
            [['user_id', 'size'], 'integer'],
            [['description'], 'string'],
            [['date_entered', 'date_updated'], 'safe'],
            [['name'], 'string', 'max' => 80],
            [['type'], 'string', 'max' => 45],
            ['type', 'validateFileType'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'type' => 'Type',
            'size' => 'Size',
            'description' => 'Description',
            'date_entered' => 'Date Entered',
            'date_updated' => 'Date Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageHasFiles()
    {
        return $this->hasMany(PageHasFile::className(), ['file_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['id' => 'page_id'])->viaTable('page_has_file', ['file_id' => 'id']);
    }
}
