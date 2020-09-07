<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

$sPicture = !empty($arBlock['PICTURE']) ? $arBlock['PICTURE'] : SITE_TEMPLATE_PATH.'/images/picture.missing.png';

?>
<?php if (!$arBlock['WIDE']) { ?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
<?php } ?>
    <?= Html::beginTag('div', [
        'class' => [
            'catalog-element-banner',
            'intec-content-wrap'
        ],
        'style' => !$arBlock['SPLIT'] ? [
            'background-image' => !$arResult['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null,
            'min-height' => $arBlock['HEIGHT']
        ] : null,
        'title' => !$arBlock['SPLIT'] ? $arBlock['NAME'] : null,
        'data' => !$arBlock['SPLIT'] ? [
            'split' => 'false',
            'lazyload-use' => $arResult['LAZYLOAD']['USE'] ? 'true' : 'false',
            'original' => $arResult['LAZYLOAD']['USE'] ? $sPicture : null
        ] : [
            'split' => 'true'
        ]

    ]) ?>
        <?php if ($arBlock['SPLIT']) { ?>
            <?= Html::tag('div', null, [
                'class' => 'catalog-element-banner-background',
                'title' => $arBlock['NAME'],
                'data' => [
                    'lazyload-use' => $arResult['LAZYLOAD']['USE'] ? 'true' : 'false',
                    'original' => $arResult['LAZYLOAD']['USE'] ? $sPicture : null
                ],
                'style' => [
                    'background-image' => !$arResult['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null,
                    'min-height' => $arBlock['HEIGHT']
                ]
            ]) ?>
         <?php } ?>
        <div class="intec-content catalog-element-banner-wrapper">
            <div class="intec-content-wrapper catalog-element-banner-wrapper-2">
                <div class="catalog-element-banner-content intec-grid intec-grid-500-wrap intec-grid-a-v-stretch intec-grid-a-h-between">
                    <div class="catalog-element-banner-information intec-grid-item-auto intec-grid-item-500-1">
                        <h1 class="catalog-element-banner-header">
                            <?= $arBlock['NAME'] ?>
                        </h1>
                        <?php if (
                            $arBlock['PRICE']['SHOW'] ||
                            $arBlock['BUTTON']['SHOW'] || (
                                $arBlock['TEXT']['SHOW'] &&
                                $arBlock['TEXT']['POSITION'] === 'inside'
                            )
                        ) { ?>
                            <div class="catalog-element-banner-information-wrapper">
                                <?php if ($arBlock['PRICE']['SHOW'] || $arBlock['BUTTON']['SHOW']) { ?>
                                    <div class="catalog-element-banner-purchase intec-grid intec-grid-500-wrap intec-grid-a-v-center intec-grid-i-5">
                                        <?php if ($arBlock['PRICE']['SHOW']) { ?>
                                            <?php if (!empty($arBlock['PRICE']['TITLE'])) { ?>
                                                <div class="catalog-element-banner-purchase-caption intec-grid-item-auto">
                                                    <?= $arBlock['PRICE']['TITLE'] ?>
                                                </div>
                                            <?php } ?>
                                            <div class="catalog-element-banner-purchase-price intec-grid-item-auto">
                                                <?= $arBlock['PRICE']['VALUE'] ?>
                                            </div>
                                        <?php } ?>
                                        <div class="intec-grid-item"></div>
                                        <?php if ($arBlock['BUTTON']['SHOW']) { ?>
                                        <?php
                                            $arFormParameters = [
                                                'id' => $arBlock['FORM']['ID'],
                                                'template' => $arBlock['FORM']['TEMPLATE'],
                                                'parameters' => [
                                                    'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'_FORM_ORDER',
                                                    'CONSENT_URL' => $arBlock['FORM']['CONSENT']
                                                ],
                                                'settings' => [
                                                    'title' => $arBlock['BUTTON']['TEXT']
                                                ],
                                                'fields' => []
                                            ];

                                            if (!empty($arBlock['FORM']['FIELDS']['SERVICE']))
                                                $arFormParameters['fields'][$arBlock['FORM']['FIELDS']['SERVICE']] = $arBlock['NAME'];
                                        ?>
                                            <div class="catalog-element-banner-purchase-button-wrap intec-grid-item-auto">
                                                <?= Html::tag('a', $arBlock['BUTTON']['TEXT'], [
                                                    'class' => [
                                                        'catalog-element-banner-purchase-button',
                                                        'intec-ui' => [
                                                            '',
                                                            'control-button',
                                                            'mod-round-3',
                                                            'scheme-current',
                                                            'size-2'
                                                        ]
                                                    ],
                                                    'onclick' => '(function() {
                                                        universe.forms.show('.JavaScript::toObject($arFormParameters).');
                                                        if (window.yandex && window.yandex.metrika) {
                                                           window.yandex.metrika.reachGoal(\'forms.open\');
                                                           window.yandex.metrika.reachGoal('.JavaScript::toObject('forms.'.$arFormParameters['id'].'.open').');
                                                       }
                                                    })()'
                                                ]) ?>
                                            </div>
                                            <?php unset($arFormParameters) ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arBlock['TEXT']['SHOW'] && $arBlock['TEXT']['POSITION'] === 'inside') { ?>
                                    <div class="catalog-element-banner-text intec-ui-markup-text">
                                        <?= $arBlock['TEXT']['VALUE'] ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ($arBlock['SPLIT'] && $arBlock['TEXT']['SHOW'] && $arBlock['TEXT']['POSITION'] == 'outside') { ?>
                            <div class="catalog-element-banner-text">
                                <?php if ($arBlock['TEXT']['HEADER']['SHOW']) { ?>
                                    <div class="catalog-element-banner-text-header intec-template-part intec-ui-markup-header">
                                        <?= $arBlock['TEXT']['HEADER']['VALUE'] ?>
                                    </div>
                                <?php } ?>
                                <div class="catalog-element-banner-text-value intec-ui-markup-text">
                                    <?= $arBlock['TEXT']['VALUE'] ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if ($arBlock['SPLIT']) { ?>
                        <div class="catalog-element-banner-picture-wrap intec-grid-item-2">
                            <?= Html::tag('div', null, [
                                'class' => 'catalog-element-banner-picture',
                                'title' => $arBlock['NAME'],
                                'data' => [
                                    'lazyload-use' => $arResult['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arResult['LAZYLOAD']['USE'] ? $sPicture : null
                                ],
                                'style' => [
                                    'background-image' => !$arResult['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null,
                                    'height' => $arBlock['HEIGHT']
                                ]
                            ]) ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?= Html::endTag('div') ?>
<?php if (!$arBlock['WIDE']) { ?>
        </div>
    </div>
<?php } ?>
<?php

unset($sPicture);