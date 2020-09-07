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

$iCount = 0;

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

$arContact = $arResult['MAIN'];
$arContactId = null;

if (!empty($arContact))
    $arContactId = $arContact['ID'];

?>

<?php foreach ($arResult['ITEMS'] as $arItem) {

    $iCount++;
    $arData = $arItem['DATA'];

    if (!$arData['MAP']['SHOW']) continue;

    $arCoordinates = $getMapCoordinates($arItem);

    $bState = $arItem['ID'] == $arContactId ? true : false;

?>
    <?php if ($iCount <= 4) { ?>
        <div class="widget-item intec-cl-background-hover intec-grid-item <?= $bState ? 'intec-cl-background' : null ?>" data-role="contacts.item" data-state="<?= $bState ? 'enabled' : 'disabled' ?>"
             data-latitude="<?= $arCoordinates[0] ?>"
             data-longitude="<?= $arCoordinates[1] ?>"
        >
            <div class="widget-item-wrapper">
                <div class="widget-item-name">
                    <?= $arItem['NAME'] ?>
                </div>
                <?php if ($arData['PHONE']['SHOW'] && $arVisual['PHONE']['SHOW']) { ?>
                    <div class="widget-item-contacts">
                        <?php if (Type::isArray($arData['PHONE']['VALUE'])) { ?>
                            <?= ArrayHelper::getFirstValue($arData['PHONE']['VALUE']) ?>
                        <?php } else { ?>
                            <?= $arData['PHONE']['VALUE'] ?>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php if ($arData['ADDRESS']['SHOW'] && $arVisual['ADDRESS']['SHOW']) { ?>
                    <div class="widget-item-address">
                        <?= $arData['ADDRESS']['VALUE'] ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="widget-item widget-item-hidden" data-role="contacts.item" data-state="disabled"></div>
    <?php } ?>
<?php } ?>
<?php if ($arVisual['FEEDBACK']['SHOW']) { ?>
<div class="widget-item-custom intec-grid-item-auto intec-grid-item-1024-1">
    <div class="widget-item-custom-wrapper">
        <div class="intec-grid intec-grid-a-v-center intec-grid-450-wrap">
            <?php if ($arVisual['FEEDBACK']['IMAGE']['SHOW'] && !empty($arVisual['FEEDBACK']['IMAGE']['VALUE'])) { ?>
                <div class="widget-item-auto intec-grid-item-450-1">
                    <?= Html::tag('div', '', [
                        'class' => 'widget-item-picture',
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
                        <div class="widget-item-contact">
                            <?= $arVisual['FEEDBACK']['TEXT']['VALUE'] ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($arVisual['FEEDBACK']['BUTTON']['VALUE'])) { ?>
                        <div class="widget-item-contact-button-wrap">
                            <?= Html::tag('div', $arVisual['FEEDBACK']['BUTTON']['VALUE'], [
                                'class' => [
                                    'widget-item-contact-button',
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
                                })()',
                            ]) ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>