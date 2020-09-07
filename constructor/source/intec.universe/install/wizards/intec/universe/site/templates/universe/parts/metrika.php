<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\Core;
use intec\core\base\Collection;
use intec\core\helpers\JavaScript;

/**
 * @var Collection $properties
 */

if ($properties->get('yandex-metrika-use')) {
    $id = $properties->get('yandex-metrika-id');

    if (!empty($id)) {
        Core::$app->web->js->addFile('https://mc.yandex.ru/metrika/tag.js');
        Core::$app->web->js->addString('
            (function () {
                window.yandex = {};
                window.yandex.metrika = new Ya.Metrika2('.JavaScript::toObject([
                    'id' => $id,
                    'accurateTrackBounce' => true,
                    'clickmap' => $properties->get('yandex-metrika-click-map'),
                    'trackHash' => $properties->get('yandex-metrika-track-hash'),
                    'trackLinks' => $properties->get('yandex-metrika-track-links'),
                    'webvisor' => $properties->get('yandex-metrika-track-webvisor')
                ]).');
                
                universe.basket.on(\'add\', function () {
                    window.yandex.metrika.reachGoal(\'basket.add\');
                });
                
                universe.basket.on(\'remove\', function () {
                    window.yandex.metrika.reachGoal(\'basket.remove\');
                });
                
                universe.basket.on(\'clear\', function () {
                    window.yandex.metrika.reachGoal(\'basket.clear\');
                });
            })()
        ');
    }

    unset($id);
}