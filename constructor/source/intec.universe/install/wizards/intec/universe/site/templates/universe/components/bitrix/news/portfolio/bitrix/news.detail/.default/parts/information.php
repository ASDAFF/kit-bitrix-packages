<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arVisual
 * @var array $arData
 */

$iGrid = count($arData['INFORMATION']);

if ($iGrid >= $arVisual['INFORMATION']['COLUMNS'] && $iGrid >= 1)
    $iGrid = $arVisual['INFORMATION']['COLUMNS'];

?>
<div class="news-detail-information intec-grid intec-grid-wrap intec-grid-i-v-25 intec-grid-i-h-15">
    <?php foreach ($arData['INFORMATION'] as $arInformation) {

        if (Type::isArray($arInformation['VALUE']))
            $arInformation['VALUE'] = implode(', ', $arInformation['VALUE'])

    ?>
        <?= Html::beginTag('div', [
            'class' => Html::cssClassFromArray([
                'news-detail-information-item' => true,
                'intec-grid-item' => [
                    $iGrid => true,
                    '1024-2' => $iGrid >= 3,
                    '768-1' => $iGrid >= 2
                ]
            ], true)
        ]) ?>
            <div class="news-detail-information-item-name intec-template-part intec-template-part-title">
                <?= $arInformation['NAME'] ?>
            </div>
            <div class="news-detail-information-item-value">
                <?= $arInformation['VALUE'] ?>
            </div>
        <?= Html::endTag('div') ?>
    <?php } ?>
</div>