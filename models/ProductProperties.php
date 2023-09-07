<?php

namespace app\models;

/**
 * This is the model class for table "product_properties".
 *
 * @property int            $id
 * @property int|null       $product_id
 * @property int|null       $property_id
 * @property int|null       $value_id
 * @property Products       $product
 * @property Properties     $property
 * @property PropertyValues $value
 */
class ProductProperties extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_properties';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'property_id', 'value_id'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Properties::class, 'targetAttribute' => ['property_id' => 'id']],
            [['value_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyValues::class, 'targetAttribute' => ['value_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'property_id' => 'Property ID',
            'value_id' => 'Value ID',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[Property]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Properties::class, ['id' => 'property_id']);
    }

    /**
     * Gets query for [[Value]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getValue()
    {
        return $this->hasOne(PropertyValues::class, ['id' => 'value_id']);
    }
}
