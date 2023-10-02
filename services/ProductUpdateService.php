<?php

namespace app\services;

use app\models\Categories;
use app\models\ProductProperties;
use app\models\Products;
use Yii;

class ProductUpdateService
{
    public function updateProduct($productId, $name, $price, $categoryName, $propertyNames, $valueNames): array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $productSaveService = new ProductSaveService();
        try {
            $product = Products::findOne(['id' => $productId]);
            if ($product) {
                $category = Categories::findOne(['name' => $categoryName]);
                if (!$category) {
                    $category = new Categories();
                    $category->name = $categoryName;
                    $category->save();
                }
                $categoryId = $category->id;

                // Обновление товара
                $product->name = $name;
                $product->price = $price;
                $product->save();

                // Удаление и сохранение свойств товара
                ProductProperties::deleteAll(['product_id' => $productId]);
                $productSaveService->saveProperties($propertyNames, $valueNames, $categoryId, $productId);

                $transaction->commit();

                return [
                    'status' => 'success',
                    'message' => 'Товар успешно обновлен',
                ];
            } else {
                $transaction->rollBack();

                return [
                    'status' => 'error',
                    'message' => 'Товар не найден',
                ];
            }
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'status' => 'error',
                'message' => 'Ошибка при обновлении товара',
            ];
        }
    }
}
