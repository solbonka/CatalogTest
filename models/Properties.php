<?php

namespace app\models;

/**
 * This is the model class for table "properties".
 *
 * @property int                 $id
 * @property string              $name
 * @property ProductProperties[] $productProperties
 * @property PropertyValues[]    $propertyValues
 */
class Properties extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'properties';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[ProductProperties]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductProperties()
    {
        return $this->hasMany(ProductProperties::class, ['property_id' => 'id']);
    }

    /**
     * Gets query for [[PropertyValues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyValues()
    {
        return $this->hasMany(PropertyValues::class, ['property_id' => 'id']);
    }
}
