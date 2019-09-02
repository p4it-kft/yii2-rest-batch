# yii2-rest-batch
common batch action for rest api
## Installation

```bash
composer require p4it/yii2-rest-batch
```

Add to urlManager config:

```php
 'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ...
                ['class' => UrlRule::class,
                    'controller' => 'v2/batch',
                    'patterns' => [
                        'PUT,PATCH,DELETE,GET,HEAD,POST,GET,HEAD' => 'batch',
                        '' => 'options',
                    ],
                    'pluralize' => false,
                ],
                ...
            ],
        ],
```

Than you will able to call on url v2/batch 

GET request could look like something like this:

```JSON
[
   {
      "path": "v2/room-slots",
      "query": {
      		"expand":"room,roomHelp",
      		"sort":"-product_id"
      },
      "body": {
         "status": "reserved",
         "team_size": "sdfsadf"
      }
   },
   {
      "path": "v2/room-slots",
      "query": {
      		"expand":"room,roomHelp",
      		"sort":"product_id"
      },
      "body": {
         "status": "reserved",
         "team_size": "sdfsadf"
      }
   },
   {
      "path": "/v2/room-slots/172247"
   }
]
```

PATCH request could look like something like this:

```JSON
[
   {
      "path": "/v2/room-slots/172248",
      "body": {
         "status": "reserved",
         "team_size": "sdfsadf"
      }
   },
   {
      "path": "/v2/room-slots/172247",
      "body": {
         "status": "reserved",
         "binder": 8
      }
   }
]
```

Response:

```JSON
[
    {
        "path": "/v2/room-slots/172248",
        "body": [
            {
                "field": "team_size",
                "message": "Team Size must be an integer."
            }
        ],
        "status": 422,
        "message": "Data Validation Failed."
    },
    {
        "path": "/v2/room-slots/172247",
        "body": {
            "id": 172247,
            "product_id": 172247,
            "room_id": 17,
            "status": "reserved",
            ...
        },
        "status": 200,
        "message": "OK"
    }
]
```

Controller:


```php
class BatchController extends Controller
{
    public function actions()
    {
        $actions['batch'] = [
            'class' => BatchAction::class,
        ];

        $actions['options'] = [
            'class' => OptionsAction::class,
            'collectionOptions' => ['PUT','PATCH','DELETE','GET','HEAD','POST','GET','HEAD','OPTIONS']
        ];

        return $actions;
    }
}
```

it can only handle one type of method at time

improvements:

- build limits
- add possibility to use multiple methods in one requests
- add headers
- create tests

