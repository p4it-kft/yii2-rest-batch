<?php
namespace p4it\rest\batch;

use yii\base\Model;

/**
 * Class BatchRequest
 * @package modules\api\common\components\action
 */
class BatchRequest extends Model
{
    public $path;
    public $query;
    public $body;

    public function rules()
    {
        return [
            ['path', 'required'],
            ['path', 'string'],
            ['body', 'safe'],
            ['query', 'safe'],
        ];
    }
}