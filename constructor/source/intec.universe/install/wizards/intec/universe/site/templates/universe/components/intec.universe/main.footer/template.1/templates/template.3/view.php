<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$bPartLeftShow = $arResult['MENU']['MAIN']['SHOW'];
$bPartRightShow =
    $arResult['SEARCH']['SHOW'] ||
    $arResult['PHONE']['SHOW'] ||
    $arResult['EMAIL']['SHOW'] ||
    $arResult['FORMS']['CALL']['SHOW'] ||
    $arResult['ADDRESS']['SHOW'];

$bPartsShow =
    $bPartLeftShow ||
    $bPartRightShow;

$bPanelShow =
    $arResult['COPYRIGHT']['SHOW'] ||
    $arResult['SOCIAL']['SHOW'] ||
    $arResult['ICONS']['SHOW'] ||
    $arResult['LOGOTYPE']['SHOW'];

?>
<div class="widget-view-3 intec-content-wrap">
    <div class="widget-wrapper intec-content">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($bPartsShow) { ?>
                <div class="<?= Html::cssClassFromArray([
                    'widget-parts',
                    'intec-grid' => [
                        '',
                        'nowrap',
                        'a-h-start',
                        'a-v-start',
                        '768-wrap'
                    ]
                ]) ?>">
                    <div class="widget-part widget-part-left intec-grid-item intec-grid-item-768-1">
                        <?php if ($bPartLeftShow) { ?>
                            <?php include(__DIR__.'/../../parts/menu/main.columns.1.php') ?>
                        <?php } ?>
                    </div>
                    <?php if ($bPartRightShow) { ?>
                        <div class="widget-part widget-part-right intec-grid-item-auto intec-grid-item-768-1">
                            <?php if ($arResult['SEARCH']['SHOW']) { ?>
                                <div class="widget-search">
                                    <?php
                                        $arSearch = [
                                            'TEMPLATE' => 'input.3'
                                        ];

                                        include(__DIR__.'/../../parts/search.php');
                                    ?>
                                </div>
                            <?php } ?>
                            <?php if (
                                $arResult['PHONE']['SHOW'] ||
                                $arResult['EMAIL']['SHOW'] ||
                                $arResult['FORMS']['CALL']['SHOW'] ||
                                $arResult['ADDRESS']['SHOW']
                            ) { ?>
                                <div class="widget-part-items intec-grid intec-grid-wrap intec-grid-a-h-start intec-grid-a-v-center">
                                    <?php if ($arResult['PHONE']['SHOW']) { ?>
                                        <div class="widget-part-item widget-phone intec-grid-item-2 intec-grid-item-550-1">
                                            <span class="widget-part-item-icon">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                            <a class="widget-part-item-text" href="tel:<?= $arResult['PHONE']['VALUE']['LINK'] ?>">
                                                <?= $arResult['PHONE']['VALUE']['DISPLAY'] ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arResult['EMAIL']['SHOW']) { ?>
                                        <div class="widget-part-item widget-email intec-grid-item-2 intec-grid-item-550-1">
                                            <span class="widget-part-item-icon">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <a class="widget-part-item-text" href="mailto:<?= $arResult['EMAIL']['VALUE'] ?>">
                                                <?= $arResult['EMAIL']['VALUE'] ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arResult['FORMS']['CALL']['SHOW']) { ?>
                                        <div class="widget-part-item widget-form intec-grid-item-2 intec-grid-item-550-1">
                                            <?= Html::tag('div', Loc::getMessage('C_MAIN_FOOTER_TEMPLATE_1_VIEW_3_FORMS_CALL_BUTTON'), [
                                                'class' => [
                                                    'intec-ui' => [
                                                        '',
                                                        'control-button',
                                                        'mod-round-3',
                                                        'scheme-current',
                                                        'size-3'
                                                    ]
                                                ],
                                                'data' => [
                                                    'action' => 'forms.call.open'
                                                ]
                                            ]) ?>
                                            <?php include(__DIR__.'/../../parts/forms/call.php') ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arResult['ADDRESS']['SHOW']) { ?>
                                        <div class="widget-part-item widget-address intec-grid-item-2 intec-grid-item-550-1">
                                            <span class="widget-part-item-icon">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                            <span class="widget-part-item-text">
                                                <?= $arResult['ADDRESS']['VALUE'] ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <div id="bx-composite-banner"></div>
            <?php if ($bPanelShow) { ?>
                <div class="widget-panel">
                    <div class="<?= Html::cssClassFromArray([
                        'widget-panel-items',
                        'intec-grid' => [
                            '',
                            'nowrap',
                            'a-h-start',
                            'a-v-center',
                            '1000-wrap'
                        ]
                    ]) ?>">
                        <?php if ($arResult['COPYRIGHT']['SHOW']) { ?>
                            <div class="widget-panel-item intec-grid-item intec-grid-item-1000-1">
                                <div class="widget-copyright">
                                    <?= $arResult['COPYRIGHT']['VALUE'] ?>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="widget-panel-item widget-panel-item-empty intec-grid-item intec-grid-item-1000-1"></div>
                        <?php } ?>
                        <?php if ($arResult['SOCIAL']['SHOW']) { ?>
                            <div class="widget-panel-item intec-grid-item-auto intec-grid-item-shrink-1 intec-grid-item-1000-1">
                                <!--noindex-->
                                <div class="widget-social">
                                    <div class="widget-social-items">
                                        <?php foreach ($arResult['SOCIAL']['ITEMS'] as $arItem) { ?>
                                            <?php if (!$arItem['SHOW']) continue ?>
                                            <div class="widget-social-item">
                                                <a rel="nofollow" href="<?= $arItem['LINK'] ?>" class="widget-social-item-icon glyph-icon-<?= StringHelper::toLowerCase($arItem['CODE']) ?>"></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!--/noindex-->
                            </div>
                        <?php } ?>
                        <?php if ($arResult['ICONS']['SHOW']) { ?>
                            <div class="widget-panel-item intec-grid-item-auto intec-grid-item-shrink-1 intec-grid-item-1000-1">
                                <div class="widget-icons intec-grid intec-grid-wrap intec-grid-a-h-center intec-grid-a-v-center intec-grid-i-8">
                                    <?php foreach ($arResult['ICONS']['ITEMS'] as $arItem) { ?>
                                        <div class="widget-icon intec-grid-item-auto" data-icon="<?= StringHelper::toLowerCase($arItem['CODE']) ?>">
                                            <div class="widget-icon-image"></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($arResult['LOGOTYPE']['SHOW']) { ?>
                            <div class="widget-panel-item intec-grid-item intec-grid-item-1000-1">
                                <div class="widget-logotype">
                                    <a href="/" class="widget-logotype-wrapper">
                                        <?php include(__DIR__.'/../../parts/logotype.php') ?>
                                    </a>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="widget-panel-item widget-panel-item-empty intec-grid-item intec-grid-item-1000-1"></div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>