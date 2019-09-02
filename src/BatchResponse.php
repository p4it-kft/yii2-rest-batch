<?php
namespace p4it\rest\batch;

use yii\base\Model;

/**
 * Class BatchResponse
 * @package modules\api\common\components\action
 */
class BatchResponse extends Model
{
    public $path;
    public $body = [];
    public $status;
    public $message;
}