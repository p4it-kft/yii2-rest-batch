<?php
namespace p4it\rest\batch;

use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\Request;
use yii\web\Response;

/**
 * Class DownloadAction
 * @package modules\api\common\components\action
 */
class BatchAction extends Action
{
    /**
     * Displays a model.
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {

        $response = [];

        foreach (Yii::$app->getRequest()->getBodyParams() as $key => $bodyParam) {
            $oldRequest = clone Yii::$app->getRequest();

            $batchRequest = new BatchRequest();
            $batchRequest->load($bodyParam, '');

            $batchResponse = new BatchResponse();
            $response[$key] = $batchResponse;

            $batchResponse->path = $batchRequest->path;

            if(!$batchRequest->validate()) {
                $batchResponse->status = 400;
                $batchResponse->message = Response::$httpStatuses[400];
                $batchResponse->body = $batchRequest->getErrors();

                continue;
            }

            Yii::$app->request->setPathInfo($batchRequest->path);
            Yii::$app->request->setQueryParams($batchRequest->query);
            Yii::$app->request->setBodyParams($batchRequest->body);

            $oldStatusCode = Yii::$app->response->getStatusCode();

            try {
                [$route, $params] = Yii::$app->request->resolve();

                $batchResponse->body = Yii::$app->runAction($route, $params);
                $batchResponse->status = Yii::$app->response->getStatusCode();
                $batchResponse->message = Yii::$app->response->statusText;
            } catch (\Exception $exception) {
                $batchResponse->body = [
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode()
                ];
                $batchResponse->status = 500;
                $batchResponse->message = Response::$httpStatuses[500];

                Yii::error($batchResponse);
            }

            Yii::$app->request = $oldRequest;
            Yii::$app->response->setStatusCode($oldStatusCode);
        }

        Yii::$app->response->setStatusCode(207);

        return $response;
    }

}