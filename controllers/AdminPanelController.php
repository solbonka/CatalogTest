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
     * Product action.
     *
     * @return string
     * @throws Throwable
     */
    public function actionProducts()
    {
        $products = Products::find()->all();
        return $this->renderAjax('products', ['products' => $products]);
    }

    /**
     * Save action.
     *
     * @return string
     */
    public function actionSave(): string
    {
        $model = new AdminPanelForm();

        $name = Yii::$app->request->post('AdminPanelForm')['name'];
        $price = Yii::$app->request->post('AdminPanelForm')['price'];
        $categoryName = Yii::$app->request->post('AdminPanelForm')['category'];
        $propertyNames = Yii::$app->request->post('property');
        $valueNames = Yii::$app->request->post('value');
        $categoryId = Categories::findOne(['name' => $categoryName])->id;
        $existingProduct = Products::findOne(['name' => $name, 'price' => $price, 'category_id' => $categoryId]);
        if ($existingProduct) {
            return $this->render(
                'save',
                ['false' => 'Такой продукт уже существует', 'model' => $model]
            );
        } else {
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
                $product->category_id = $categoryId;
                $product->save();

                $productId = $product->id;

                // Добавляем свойства и значения продукта
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
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Товар добавлен или обновлен успешно.');

                return $this->render(
                    'save',
                    ['success' => 'Товар успешно добавлен или обновлен', 'model' => $model]
                );
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash(
                    'error',
                    'Произошла ошибка при добавлении или обновлении товара: ' . $e->getMessage()
                );

                return $this->render(
                    'save',
                    ['false' => 'Не удалось добавить товар', 'model' => $model]
                );
            }
        }
    }

    /**
     * Update action.
     *
     * @return string
     */
    public function actionUpdate(): string
    {
        $model = new AdminPanelForm();
        $requestData = Yii::$app->request->post('AdminPanelForm');
        $productId = $requestData['id'];
        $name = $requestData['name'];
        $price = $requestData['price'];
        $categoryName = $requestData['category'];
        $propertyNames = Yii::$app->request->post('property');
        $valueNames = Yii::$app->request->post('value');

        $convertedData = [
            'id' => intval($productId),
            'name' => $name,
            'price' => intval($price),
            'category' => $categoryName,
            'property' => $propertyNames,
            'value' => $valueNames,
        ];

        $product = Products::findOne(['id' => $productId]);
        $category = $product->category;
        $arrayProduct = $product->toArray();
        unset($arrayProduct['category_id']);
        $arrayProperties = [];
        $arrayValues = [];
        foreach ($product->productProperties as $productProperty) {
            $arrayProperties[] = $productProperty->property->name;
            $arrayValues[] = $productProperty->value->value;
        }
        $arrayProduct['category'] = $category->name;
        $arrayProduct['property'] = $arrayProperties;
        $arrayProduct['value'] = $arrayValues;

        if ($convertedData === $arrayProduct) {
            Yii::$app->session->setFlash('success', 'Вы ничего не изменили');

            return $this->render(
                'update',
                ['success' => 'Вы ничего не изменили', 'model' => $model]
            );
        } else {
            $category = Categories::findOne($convertedData['category']);
            if (!$category) {
                $category = new Categories();
                $category->name = $convertedData['category'];
                $category->save();
            }

            if ($arrayProduct['name'] !== $convertedData['name'] || $arrayProduct['price'] !== $convertedData['price']) {
                $product->name = $convertedData['name'];
                $product->price = $convertedData['price'];
                $product->save();
            }

            if ($arrayProduct['property'] !== $convertedData['property'] || $arrayProduct['value'] !== $convertedData['value']) {
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
                            'category_id' => $category->id,
                            'product_id' => $productId,
                            'property_id' => $propertyId,
                            'value_id' => $valueId,
                        ]
                    );
                    if (!$productProperty) {
                        $productProperty = new ProductProperties();
                        $productProperty->category_id = $category->id;
                        $productProperty->product_id = $productId;
                        $productProperty->property_id = $propertyId;
                        $productProperty->value_id = $valueId;
                        $productProperty->save();
                    }
                }
            }
            Yii::$app->session->setFlash('success', 'Товар успешно обновлен');

            return $this->render(
                'update',
                ['success' => 'Товар успешно обновлен', 'model' => $model]
            );
        }
    }
}
