<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arVisual
 * @var array $arData
 */

$iGrid = count($arData['CHARACTERISTICS']);

if ($iGrid >= 3) {
    if ($iGrid >= $arVisual['CHARACTERISTICS']['COLUMNS'])
        $iGrid = $arVisual['CHARACTERISTICS']['COLUMNS'];
} else
    $iGrid = 3;


?>
<?= Html::beginTag('div', [
    'class' => [
        'news-detail-characteristics',
        'intec-grid' => [
            '',
            'wrap',
            'i-h-15',
            'i-v-25'
        ]
    ]
]) ?>
    <?php foreach ($arData['CHARACTERISTICS'] as $arCharacteristic) { ?>
        <?= Html::beginTag('div', [
            'class' => Html::cssClassFromArray([
                'news-detail-characteristics-item' => true,
                'intec-grid-item' => [
                    $iGrid => true,
                    '1024-4' => $iGrid >= 5,
                    '768-3' => $iGrid >= 4,
                    '650-2' => $iGrid >= 3,
                    '400-1' => true
                ]
            ], true)
        ]) ?>
            <div class="news-detail-characteristics-item-name">
                <?= $arCharacteristic['NAME'] ?>
            </div>
            <div class="news-detail-characteristics-item-value">
                <?php if (Type::isArray($arCharacteristic['VALUE'])) { ?>
                    <?= implode(', ', $arCharacteristic['VALUE']) ?>
                <?php } else { ?>
                    <?= $arCharacteristic['VALUE'] ?>
                <?php } ?>
            </div>
        <?= Html::endTag('div') ?>
    <?php } ?>
<?= Html::endTag('div') ?>