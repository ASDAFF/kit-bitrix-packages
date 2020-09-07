<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\handling\Handler;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Json;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var string $directory
 */

$requested = false;
$response = null;

if ($request->getIsAjax() && $request->getIsPost()) {
    $parameters = $request->post('ajax');

    if (Type::isArray($parameters)) {
        $parameters = ArrayHelper::merge([
            'action' => null,
            'data' => null,
            'request' => null
        ], $parameters);

        $requested = $parameters['request'];
        $requested = $requested === 'y' && !empty($parameters['action']);

        if ($requested) {
            $handler = new Handler(
                $directory.'/ajax',
                'intec\template\ajax'
            );

            $response = $handler->handle(
                $parameters['action'],
                $parameters['data']
            );

            $response = StringHelper::convert(Json::encode($response));
        }

        unset($action);
    }

    unset($parameters);
}

if (!$requested) {
    $parameters = $request->get('page');

    if (Type::isArray($parameters)) {
        $parameters = ArrayHelper::merge([
            'page' => null,
            'request' => null
        ], $parameters);

        $requested = $parameters['request'];
        $requested = $requested === 'y' && !empty($parameters['page']);

        if ($requested) {
            $handler = new Handler(
                $directory.'/pages',
                'intec\template\pages'
            );

            $response = $handler->handle($parameters['page']);
            $response = StringHelper::convert($response);
        }
    }

    unset($parameters);
}

if ($requested) {
    if (!empty($response))
        echo $response;

    exit();
}

unset($response);
unset($requested);