<?php namespace App\controllers;

require_once "../backend/app/controllers/Controller.php";
require_once "../backend/app/models/User.php";
require_once "../backend/common/models/response/Response.php";

use app\common\models\ModelApp;
use app\controllers\Controller;
use app\models\User;
use app\common\response\Response;
use Exception;

class ResponseController extends Controller
{
    public function actionGet(User $model): string
    {
        $response = new Response();

        try {
            $params = $_GET;
            $requiredFields = $model->setProps($params);

            if ($this->isFilled($requiredFields) && $model->definedUser($requiredFields)) {
                if ($model->hasErrors()) {

                    return $response->getSuccess($model->getErrors());
                }

                $result = [
                    "model" => $model,
                    "message" => "Данные успешно обновлены"
                ];

                return $response->getSuccess($result);
            } else {
                $model->setError(get_called_class(), "Не все необходимые заголовки заполнены");
            }

            if ($model->hasErrors()) {

                return $response->getSuccess($model->getErrors());
            }
        } catch (Exception $exception) {

            return $response->getExceptionError($exception);
        }

        return '';
    }

    public function actionPost(User $model): string
    {
        $response = new Response();

        try {
            $json = file_get_contents('php://input');
            $params = json_decode($json, JSON_OBJECT_AS_ARRAY);

            $requiredFields = $model->setProps($params);

            if ($this->isFilled($requiredFields) && $model->definedUser($requiredFields)) {

                if ($model->hasErrors()) {

                    return $response->getSuccess($model->getErrors());
                }

                $result = [
                    "model" => $model,
                    "params" => $params,
                    "message" => "Данные успешно обновлены"
                ];

                return $response->getSuccess($result);
            } else {
                $model->setError(get_called_class(), "Не все необходимые заголовки заполнены");
            }

            if ($model->hasErrors()) {

                return $response->getSuccess($model->getErrors());
            }
        } catch (Exception $exception) {
            return $response->getExceptionError($exception);
        }

        return '';
    }

    public function actionErrors(): string
    {
        $errorModel = new ErrorModel(get_called_class(), 'Неверно составлен запрос');

        return (new Response())->getModelErrors([$errorModel]);
    }
}