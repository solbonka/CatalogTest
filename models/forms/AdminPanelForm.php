<?php

namespace app\models\forms;

use yii\base\Model;

/**
 * AdminPanelForm is the model behind the contact form.
 */
class AdminPanelForm extends Model
{
    public $name;
    public $price;
    public $category;
    public $property = [];
    public $value = [];


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'price', 'category'], 'required'],
            ['price', 'number'],
            [['property', 'value'], 'safe'],
            ['property', 'required', 'message' => 'Хотя бы одно свойство должно быть задано'],
            ['value', 'required', 'message' => 'Хотя бы одно значение для свойства должно быть задано'],
            [['property', 'value'], 'validatePropertyWithValue'],
        ];
    }

    public function validatePropertyWithValue($attribute)
    {
        foreach ($this->$attribute as $key => $property) {
            $value = $this->value[$key] ?? null;

            if (empty(trim($property)) && !empty(trim($value))) {
                $this->addError($attribute . '.' . $key, 'Свойство не может быть пустым, если значение задано');
            }

            if (!empty(trim($property)) && empty(trim($value))) {
                $this->addError($attribute . '.' . $key, 'Значение не может быть пустым, если свойство задано');
            }
        }
    }
}
