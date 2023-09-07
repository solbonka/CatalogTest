<?php

namespace app\controllers;

use app\models\Categories;
use app\models\forms\LoginForm;
use app\models\ProductProperties;
use app\models\Products;
use app\models\Properties;
use app\models\PropertyValues;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $categories = Categories::find()->all();
        $products = Products::find()->all();
        $properties = Properties::find()->all();

        return $this->render(
            'index',
            compact('categories', 'products', 'properties')
        );
    }

    /**
     * Catalog action.
     *
     * @return Response|string
     */
    public function actionCatalog()
    {
        $currentPropertyValuesArray = [];
        $currentProductPropertiesArray = [];

        $categoryId = Yii::$app->request->post('category');
        if (count(Yii::$app->request->post()) > 2) {
            $currentPropertyValueIds = array_slice(Yii::$app->request->post(), 2);
            $isCurrentPropertyValueIds = array_filter($currentPropertyValueIds, function ($value) {
                return !empty($value);
            });
            if (
                (empty($currentPropertyValueIds[array_key_first($currentPropertyValueIds)])
                    && count($currentPropertyValueIds) === 1) || empty($isCurrentPropertyValueIds)
            ) {
                $products = Products::find()
                    ->andFilterWhere(['category_id' => $categoryId])
                    ->all();

                return $this->renderAjax('_products', ['products' => $products]);
            }

            foreach ($currentPropertyValueIds as $currentPropertyValueId) {
                $currentPropertyValuesArray[] = PropertyValues::find()->where(['id' => $currentPropertyValueId])->all();
            }

            $currentPropertyValuesArray = array_filter($currentPropertyValuesArray, function ($element) {
                return !empty($element);
            });

            foreach ($currentPropertyValuesArray as $currentPropertyValues) {
                foreach ($currentPropertyValues as $currentPropertyValue) {
                    if (
                        !in_array(
                            ProductProperties::find()->andFilterWhere([
                            'property_id' => $currentPropertyValue->property->id,
                            'value_id' => $currentPropertyValue->id])->all(),
                            $currentProductPropertiesArray
                        )
                    ) {
                        $currentProductPropertiesArray[] = ProductProperties::find()->andFilterWhere([
                            'property_id' => $currentPropertyValue->property->id,
                            'value_id' => $currentPropertyValue->id])->all();
                    }
                }
            }

            $products = [];
            if (!in_array([], $currentProductPropertiesArray)) {
                foreach ($currentProductPropertiesArray as $currentProductProperties) {
                    foreach ($currentProductProperties as $currentProductProperty) {
                        if (!in_array($currentProductProperty->product, $products)) {
                            $products[] = $currentProductProperty->product;
                        }
                    }
                }
            }

            return $this->renderAjax('_products', ['products' => $products]);
        }
        $properties = Properties::find()->where(['category_id' => $categoryId])->all();
        if (!empty($categoryId)) {
            $products = Products::find()
                ->andFilterWhere(['category_id' => $categoryId])
                ->all();
        } else {
            $products = Products::find()->all();
        }

        return $this->asJson(['products' => $this->renderAjax('_products', ['products' => $products]),
            'properties' => $this->renderAjax('_properties', ['properties' => $properties])]);
    }

    /**
     * Catalog action.
     *
     * @return Response|string
     */
    public function actionForm()
    {
        $category = Categories::findOne(Yii::$app->request->post('category'));
        var_dump($category);
        $properties = Yii::$app->request->post();
        // Получаем отфильтрованные продукты на основе выбранной категории и свойств
        $products = Products::find()
            ->andFilterWhere(['category_id' => $category])
            ->andFilterWhere($properties)
            ->all();

        return $this->renderAjax('_products', ['products' => $products]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
