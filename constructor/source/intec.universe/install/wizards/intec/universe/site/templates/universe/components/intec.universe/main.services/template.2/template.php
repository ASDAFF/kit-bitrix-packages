<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];

$arForm = [];

if ($arVisual['FORM']['USE']) {
    $arForm = $arVisual['FORM'];
    $arForm['PARAMETERS'] = [
        'id' => $arForm['ID'],
        'template' => $arForm['TEMPLATE'],
        'parameters' => [
            'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'_FORM_ASK',
            'CONSENT_URL' => $arForm['CONSENT']
        ],
        'settings' => [
            'title' => $arForm['TITLE']
        ]
    ];
}

$iCount = 0;

?>
<div class="widget c-services c-services-template-2" id="<?= $sTemplateId ?>">
    <div class="widget-wrapper intec-content">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
                <div class="widget-header">
                    <?php if ($arBlocks['HEADER']['SHOW']) { ?>
                        <div class="widget-title align-<?= $arBlocks['HEADER']['POSITION'] ?>">
                            <?= Html::encode($arBlocks['HEADER']['TEXT']) ?>
                        </div>
                    <?php } ?>
                    <?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
                        <div class="widget-description align-<?= $arBlocks['DESCRIPTION']['POSITION'] ?>">
                            <?= Html::encode($arBlocks['DESCRIPTION']['TEXT']) ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="widget-content">
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-items',
                        'intec-grid' => [
                            '',
                            'wrap',
                            'a-v-start',
                            'a-h-start',
                            'i-7'
                        ]
                    ]
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        if ($iCount >= 5)
                            $iCount = 0;

                        $iCount++;
                        $arData = $arItem['DATA'];
                        $sPicture = $arItem['PREVIEW_PICTURE'];

                        if (empty($sPicture))
                            $sPicture = $arItem['DETAIL_PICTURE'];

                        if (!empty($sPicture)) {
                            $sPicture = CFile::ResizeImageGet(
                                $sPicture, [
                                    'width' => 900,
                                    'height' => 900
                                ],
                                BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                            );

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                        if ($arVisual['VIEW'] === 'mosaic') {
                            $arGrid = [
                                '2' => $iCount <= 2,
                                '3' => $iCount > 2,
                                '1000-2' => $iCount > 2,
                                '600-1' => true
                            ];
                            $sDataGrid = $iCount < 3 ? '2' : '3';
                        } else {
                            $arGrid = [
                                $arVisual['COLUMNS'] => true,
                                '1000-2' => $arVisual['COLUMNS'] > 2,
                                '600-1' => true
                            ];
                            $sDataGrid = $arVisual['COLUMNS'];
                        }

                        if ($arVisual['FORM']['USE'])
                            $arForm['PARAMETERS']['fields'] = [
                                $arForm['FIELD'] => $arItem['NAME']
                            ];

                    ?>
                        <?= Html::beginTag( $arVisual['BUTTON']['SHOW'] ? 'div' : 'a', [
                            'class' => Html::cssClassFromArray([
                                'widget-item' => true,
                                'intec-grid-item' => $arGrid,
                            ], true),
                            'data' => [
                                'grid' => $sDataGrid
                            ],
                            'href' => $arVisual['BUTTON']['SHOW'] ? false : $arItem['DETAIL_PAGE_URL']
                        ]) ?>
                            <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                                <?= Html::tag('div', '', [
                                    'class' => 'widget-item-picture',
                                    'data' => [
                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                    ],
                                    'style' => [
                                        'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                    ]
                                ]) ?>
                                <div class="widget-item-fade"></div>
                                <div class="widget-item-text">
                                    <div class="widget-item-name">
                                        <?= $arItem['NAME'] ?>
                                    </div>
                                    <?php if ($arVisual['PRICE']['SHOW'] && !empty($arData['PRICE'])) { ?>
                                        <div class="widget-item-price">
                                            <?= $arData['PRICE'] ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arVisual['BUTTON']['SHOW']) { ?>
                                        <div class="widget-item-button-wrapper">
                                            <?php if ($arVisual['BUTTON']['TYPE'] === 'order' && $arForm['USE']) { ?>
                                                <?= Html::tag('div', $arVisual['BUTTON']['TEXT'], [
                                                    'class' => [
                                                        'widget-item-button',
                                                        'intec-ui' => [
                                                            '',
                                                            'size-2',
                                                            'scheme-current',
                                                            'control-button',
                                                            'mod-round-5'
                                                        ]
                                                    ],
                                                    'onclick' => '(function() {
                                                        universe.forms.show('.JavaScript::toObject($arForm['PARAMETERS']).');
                                                        
                                                        if (window.yandex && window.yandex.metrika) {
                                                            window.yandex.metrika.reachGoal(\'forms.open\');
                                                            window.yandex.metrika.reachGoal('.JavaScript::toObject('forms.'.$arForm['PARAMETERS']['id'].'.open').');
                                                        }
                                                    })()'
                                                ]) ?>
                                            <?php } else { ?>
                                                <?= Html::tag('a', $arVisual['BUTTON']['TEXT'], [
                                                    'class' => [
                                                        'widget-item-button',
                                                        'intec-ui' => [
                                                            '',
                                                            'size-2',
                                                            'scheme-current',
                                                            'control-button',
                                                            'mod-round-5'
                                                        ]
                                                    ],
                                                    'href' => $arItem['DETAIL_PAGE_URL']
                                                ]) ?>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?= Html::endTag($arVisual['BUTTON']['SHOW'] ? 'div' : 'a') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
            <?php if ($arBlocks['FOOTER']['SHOW']) { ?>
                <div class="widget-footer align-<?= $arBlocks['FOOTER']['POSITION'] ?>">
                    <?php if ($arBlocks['FOOTER']['BUTTON']['SHOW']) { ?>
                        <?= Html::tag('a', $arBlocks['FOOTER']['BUTTON']['TEXT'], [
                            'href' => $arBlocks['FOOTER']['BUTTON']['LINK'],
                            'class' => [
                                'widget-footer-button',
                                'intec-ui' => [
                                    '',
                                    'size-5',
                                    'scheme-current',
                                    'control-button',
                                    'mod' => [
                                        'transparent',
                                        'round-half'
                                    ]
                                ]
                            ]
                        ]) ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>