<?php

namespace app\services;

use app\models\Categories;
use app\models\ProductProperties;
use app\models\Products;
use app\models\Properties;
use app\models\PropertyValues;
use Exception;
use Yii;

class ProductSaveService
{
    public function saveProduct($name, $price, $categoryName, $propertyNames, $valueNames): array
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $category = Categories::findOne(['name' => $categoryName]);
            if (!$category) {
                $category = new Categories();
                $category->name = $categoryName;
                $category->save();
            }

            $product = new Products();
            $product->name = $name;
            $product->price = $price;
            $product->category_id = $category->id;
            $product->save();

            $productId = $product->id;

            // Добавляем свойства и значения продукта
            $this->saveProperties($propertyNames, $valueNames, $category->id, $productId);

            $transaction->commit();

            return [
                'status' => 'success',
                'message' => 'Товар успешно добавлен или обновлен',
            ];
        } catch (Exception $e) {
            $transaction->rollBack();

            return [
                'status' => 'error',
                'message' => 'Произошла ошибка при добавлении или обновлении товара: ' . $e->getMessage(),
            ];
        }
    }
    public function saveProperties(array $propertyNames, array $valueNames, int $categoryId, int $productId): void
    {
        foreach ($propertyNames as $index => $propertyName) {
            $property = Properties::findOne(['name' => $propertyName]);
            if (!$property) {
                $property = new Properties();
                $property->name = $propertyName;
                $property->save();
            }

            $propertyId = $property->id;
            $propertyValue = PropertyValues::findOne([
                'value' => $valueNames[$index],
                'property_id' => $propertyId]);
            if (!$propertyValue) {
                $propertyValue = new PropertyValues();
                $propertyValue->value = $valueNames[$index];
                $propertyValue->property_id = $propertyId;
                $propertyValue->save();
            }

            $valueId = $propertyValue->id;
            $productProperty = ProductProperties::findOne(
                [
                    'category_id' => $categoryId,
                    'product_id' => $productId,
                    'property_id' => $propertyId,
                    'value_id' => $valueId,
                ]
            );

            if (!$productProperty) {
                $productProperty = new ProductProperties();
                $productProperty->category_id = $categoryId;
                $productProperty->product_id = $productId;
                $productProperty->property_id = $propertyId;
                $productProperty->value_id = $valueId;

                $productProperty->save();
            }
        }
    }
}
