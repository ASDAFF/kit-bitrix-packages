<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arIcons
 */

$iCounter = 0;

$arVisual = $arResult['VISUAL'];

$arForm = [];

if (!empty($arResult['FORM']['ID'])) {
    $arForm = [
        'id' => $arResult['FORM']['ID'],
        'template' => $arVisual['FEEDBACK']['TEMPLATE'],
        'parameters' => [
            'AJAX_OPTION_ADDITIONAL' => $sTemplateId . '_FORM_ASK',
            'CONSENT_URL' => $arResult['FORM']['CONSENT']
        ],
        'settings' => [
            'title' => $arResult['FORM']['NAME']
        ]
    ];
}

?>
<?php foreach ($arResult['ITEMS'] as $arItem) {

    $iCounter++;

    if ($iCounter > 3) continue;

    $arData = $arItem['DATA'];

    if (empty($arData['MAP']['LOCATION'])) continue;

    $arCoordinates = $getMapCoordinates($arItem);
?>
    <?= Html::beginTag('div', [
        'class' => Html::cssClassFromArray([
            'news-list-short-item' => true,
            'intec-cl-background-hover' => true,
            'intec-grid-item' => true,
            'intec-cl-background' => $arItem['ID'] == $arParams['MAIN'] ? true : false
        ], true),
        'data' => [
            'state' => $arItem['ID'] == $arParams['MAIN'] ? 'enabled' : 'disabled',
            'latitude' => $arCoordinates[0],
            'longitude' => $arCoordinates[1],
            'role' => 'button',
            'list' => 'main'
        ],
    ]) ?>
        <div class="news-list-short-item-wrapper">
            <div class="news-list-short-item-name">
                <?= $arData['MAP']['NAME'] ?>
            </div>
            <?php if (!empty($arData['PHONE']) && $arVisual['PHONE']['SHOW']) { ?>
                <div class="news-list-short-item-contacts">
                    <?php if (Type::isArray($arData['PHONE'])) { ?>
                        <?= ArrayHelper::getFirstValue($arData['PHONE']) ?>
                    <?php } else { ?>
                        <?= $arData['PHONE'] ?>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if (!empty($arData['ADDRESS']) && $arVisual['ADDRESS']['SHOW']) { ?>
                <div class="news-list-short-item-address">
                    <?= $arData['ADDRESS'] ?>
                </div>
            <?php } ?>
        </div>
    <?= Html::endTag('div') ?>
<?php } ?>
<?php if ($arVisual['FEEDBACK']['SHOW']) { ?>
<div class="news-list-short-item-custom intec-grid-item-auto intec-grid-item-1024-1">
    <div class="news-list-short-item-custom-wrapper">
        <div class="intec-grid intec-grid-a-v-center intec-grid-450-wrap">
            <?php if ($arVisual['FEEDBACK']['IMAGE']['SHOW'] && !empty($arVisual['FEEDBACK']['IMAGE']['VALUE'])) { ?>
                <div class="news-list-item-auto intec-grid-item-450-1">
                    <?= Html::tag('div', '', [
                        'class' => 'news-list-short-item-picture',
                        'data' => [
                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                            'original' => $arVisual['LAZYLOAD']['USE'] ? $arVisual['FEEDBACK']['IMAGE']['VALUE'] : null
                        ],
                        'style' => [
                            'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$arVisual['FEEDBACK']['IMAGE']['VALUE'].'\')' : null
                        ]
                    ]) ?>
                </div>
            <?php } ?>
            <?php if ($arVisual['FEEDBACK']['TEXT']['SHOW'] || !empty($arVisual['FEEDBACK']['BUTTON']['VALUE'])) { ?>
                <div class="intec-grid-item intec-grid-item-450-1">
                    <?php if ($arVisual['FEEDBACK']['TEXT']['SHOW'] && !empty($arVisual['FEEDBACK']['TEXT']['VALUE'])) { ?>
                        <div class="news-list-short-item-contact">
                            <?= $arVisual['FEEDBACK']['TEXT']['VALUE'] ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($arVisual['FEEDBACK']['BUTTON']['VALUE'])) { ?>
                        <div class="news-list-short-item-contact-button-wrap">
                            <?= Html::tag('div', $arVisual['FEEDBACK']['BUTTON']['VALUE'], [
                                'class' => [
                                    'news-list-short-item-contact-button',
                                    'intec-ui' => [
                                        '',
                                        'control-button',
                                        'scheme-current',
                                        'size-3'
                                    ]
                                ],
                                'onclick' => '(function() {
                                    universe.forms.show('.JavaScript::toObject($arForm).');
                                    if (window.yandex && window.yandex.metrika) {
                                       window.yandex.metrika.reachGoal(\'forms.open\');
                                       window.yandex.metrika.reachGoal('.JavaScript::toObject('forms.'.$arForm['id'].'.open').');
                                   }
                                })()'
                            ]) ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>