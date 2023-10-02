<?php

namespace app\controllers;

use app\models\Categories;
use app\models\forms\AdminPanelForm;
use app\models\ProductProperties;
use app\models\Products;
use app\models\Properties;
use app\models\PropertyValues;
use app\services\ProductSaveService;
use app\services\ProductUpdateService;
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

        $productSaveService = new ProductSaveService();
        $result = $productSaveService->saveProduct($name, $price, $categoryName, $propertyNames, $valueNames);

        if ($result['status'] === 'success') {
            Yii::$app->session->setFlash('success', $result['message']);
        } else {
            Yii::$app->session->setFlash('error', $result['message']);
        }

        return $this->render('save', ['success' => $result['message'], 'model' => $model]);
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
        $productUpdateService = new ProductUpdateService();
        $result = $productUpdateService->updateProduct($productId, $name, $price, $categoryName, $propertyNames, $valueNames);
        if ($result['status'] === 'success') {
            Yii::$app->session->setFlash('success', $result['message']);
        } else {
            Yii::$app->session->setFlash('error', $result['message']);
        }

        return $this->render('update', ['success' => $result['message'], 'model' => $model]);
    }
}
