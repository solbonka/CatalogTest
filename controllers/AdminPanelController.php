<?php

namespace app\controllers;

use app\models\Categories;
use app\models\forms\AdminPanelForm;
use app\models\ProductProperties;
use app\models\Products;
use app\models\Properties;
use app\models\PropertyValues;
use Exception;
use Throwable;
use Yii;
use yii\web\Controller;

class AdminPanelController extends Controller
{
    /**
     * Panel action.
     *
     * @return string
     */
    public function actionPanel()
    {
        $model = new AdminPanelForm();

        return $this->render('admin-panel', ['model' => $model]);
    }

    /**
     * Update action.
     *
     * @return string
     * @throws Throwable
     */
    public function actionUpdate(): string
    {
        $model = new AdminPanelForm();
        $name = Yii::$app->request->post('AdminPanelForm')['name'];
        $price = Yii::$app->request->post('AdminPanelForm')['price'];
        $categoryName = Yii::$app->request->post('AdminPanelForm')['category'];
        $propertyNames = Yii::$app->request->post('property');
        $valueNames = Yii::$app->request->post('value');

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $category = new Categories();
            $category->name = $categoryName;

            if (Categories::findOne(['name' => $categoryName])) {
                $category->update();
            } else {
                $category->save();
            }
            $categoryId = Categories::findOne(['name' => $categoryName])->id;

            $existingProduct = Products::findOne(['name' => $name, 'price' => $price, 'category_id' => $categoryId]);
            if ($existingProduct) {
                $productId = $existingProduct->id;
                // Добавляем свойства и значения продукта
                foreach ($propertyNames as $index => $propertyName) {
                    $property = Properties::findOne(['name' => $propertyName, 'category_id' => $categoryId]);
                    if (!$property) {
                        $property = new Properties();
                        $property->name = $propertyName;
                        $property->category_id = $categoryId;
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

                    $existingProductProperty = ProductProperties::findOne(
                        [
                            'product_id' => $productId,
                            'property_id' => $propertyId,
                            'value_id' => $valueId,
                        ]
                    );
                    if (!$existingProductProperty) {
                        $productProperty = new ProductProperties();
                        $productProperty->product_id = $productId;
                        $productProperty->property_id = $propertyId;
                        $productProperty->value_id = $valueId;
                        $productProperty->save();
                    }
                }
            } else {
                $product = new Products();
                $product->name = $name;
                $product->price = $price;
                $product->category_id = $categoryId;
                $product->save();

                $productId = $product->id;

                // Добавляем свойства и значения продукта
                foreach ($propertyNames as $index => $propertyName) {
                    $property = new Properties();
                    $property->name = $propertyName;
                    $property->category_id = $categoryId;
                    $property->save();
                    $propertyId = $property->id;

                    $propertyValue = new PropertyValues();
                    $propertyValue->value = $valueNames[$index];
                    $propertyValue->property_id = $propertyId;
                    $propertyValue->save();
                    $valueId = $propertyValue->id;

                    $productProperty = new ProductProperties();
                    $productProperty->product_id = $productId;
                    $productProperty->property_id = $propertyId;
                    $productProperty->value_id = $valueId;
                    $productProperty->save();
                }
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Товар добавлен или обновлен успешно.');

            return $this->render(
                'update',
                ['success' => 'Товар успешно добавлен или обновлен', 'model' => $model]
            );
        } catch (Exception $e) {
            $transaction->rollBack();

            Yii::$app->session->setFlash(
                'error',
                'Произошла ошибка при добавлении или обновлении товара: ' . $e->getMessage()
            );

            return $this->render(
                'update',
                ['false' => 'Не удалось добавить или обновить товар', 'model' => $model]
            );
        }
    }
}
