<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

$vTextButton = include(__DIR__.'/buttons/view.'.$arVisual['BUTTON']['VIEW'].'.php');

?>
<?php return function (&$arData) use (&$arVisual, &$vTextButton) { ?>
    <?= Html::beginTag('div', [
        'class' => Html::cssClassFromArray([
            'widget-item-text' => true,
            'intec-grid-item' => [
                'auto' => !$arData['TEXT']['HALF'],
                '2' => $arData['TEXT']['HALF'] || $arData['PICTURE']['SHOW'],
                '768-1' => true,
                'a-center' => true
            ]
        ], true)
    ]) ?>
        <?php if ($arVisual['OVER']['SHOW'] && !empty($arData['OVER'])) { ?>
            <?= Html::tag('div', $arData['OVER'], [
                'class' => 'widget-item-over',
                'data' => [
                    'view' => $arVisual['OVER']['VIEW']
                ]
            ]) ?>
        <?php } ?>
        <?php if ($arVisual['HEADER']['SHOW'] && !empty($arData['HEADER'])) { ?>
            <?= Html::tag('div', $arData['HEADER'], [
                'class' => 'widget-item-header',
                'data' => [
                    'view' => $arVisual['HEADER']['VIEW']
                ]
            ]) ?>
        <?php } ?>
        <?php if ($arVisual['DESCRIPTION']['SHOW'] && !empty($arData['DESCRIPTION'])) { ?>
            <?= Html::tag('div', $arData['DESCRIPTION'], [
                'class' => 'widget-item-description',
                'data' => [
                    'view' => $arVisual['DESCRIPTION']['VIEW']
                ]
            ]) ?>
        <?php } ?>
        <?php if ($arData['BUTTON']['SHOW']) {

            if (empty($arData['BUTTON']['TEXT']))
                $arData['BUTTON']['TEXT'] = Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_2_TEMPLATE_BUTTON_TEXT_DEFAULT');

        ?>
            <?= Html::beginTag('div', [
                'class' => 'widget-item-buttons',
                'data' => [
                    'view' => $arVisual['BUTTON']['VIEW']
                ]
            ]) ?>
                <?php $vTextButton(
                    $arData['LINK']['VALUE'],
                    $arData['LINK']['BLANK'],
                    $arData['BUTTON']['TEXT']
                ) ?>
            <?= Html::endTag('div') ?>
        <?php } ?>
    <?= Html::endTag('div') ?>
<?php } ?>