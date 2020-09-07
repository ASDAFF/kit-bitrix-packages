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

$bPartLeftShow =
    $arResult['ICONS']['SHOW'] ||
    $arResult['COPYRIGHT']['SHOW'];

$bPartCenterShow = $arResult['MENU']['MAIN']['SHOW'];
$bPartRightShow =
    $arResult['SEARCH']['SHOW'] ||
    $arResult['PHONE']['SHOW'] ||
    $arResult['SOCIAL']['SHOW'] ||
    $arResult['LOGOTYPE']['SHOW'];

$bPartsShow =
    $bPartLeftShow ||
    $bPartCenterShow ||
    $bPartRightShow;

?>
<div class="widget-view-2 intec-content-wrap">
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
                    <?php if ($bPartLeftShow) { ?>
                        <div class="widget-part widget-part-left intec-grid-item-auto intec-grid-item-768-1">
                            <div class="widget-part-items">
                                <?php if ($arResult['ICONS']['SHOW']) { ?>
                                    <div class="widget-part-item">
                                        <div class="widget-icons intec-grid intec-grid-wrap intec-grid-a-h-start intec-grid-a-h-768-center intec-grid-a-v-center intec-grid-i-8">
                                            <?php foreach ($arResult['ICONS']['ITEMS'] as $arItem) { ?>
                                                <div class="widget-icon intec-grid-item-auto" data-icon="<?= StringHelper::toLowerCase($arItem['CODE']) ?>">
                                                    <div class="widget-icon-image"></div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($arResult['COPYRIGHT']['SHOW']) { ?>
                                    <div class="widget-part-item">
                                        <div class="widget-copyright">
                                            <?= $arResult['COPYRIGHT']['VALUE'] ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="widget-part-item">
                                    <div id="bx-composite-banner"></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="widget-part widget-part-center intec-grid-item intec-grid-item-768-1">
                        <?php if ($bPartCenterShow) { ?>
                            <?php include(__DIR__.'/../../parts/menu/main.columns.1.php') ?>
                        <?php } ?>
                    </div>
                    <?php if ($bPartRightShow) { ?>
                        <div class="widget-part widget-part-right intec-grid-item-auto intec-grid-item-768-1">
                            <div class="widget-part-items">
                                <?php if ($arResult['SEARCH']['SHOW']) { ?>
                                    <div class="widget-part-item widget-search">
                                        <?php
                                            $arSearch = [
                                                'TEMPLATE' => 'input.3'
                                            ];

                                            include(__DIR__.'/../../parts/search.php');
                                        ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arResult['PHONE']['SHOW']) { ?>
                                    <div class="widget-part-item widget-phone">
                                        <span class="widget-phone-icon">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <a class="widget-phone-text" href="tel:<?= $arResult['PHONE']['VALUE']['LINK'] ?>">
                                            <?= $arResult['PHONE']['VALUE']['DISPLAY'] ?>
                                        </a>
                                        <?php if ($arResult['FORMS']['CALL']['SHOW']) { ?>
                                            <div class="widget-phone-order">
                                                <div class="widget-phone-order-wrapper intec-cl-text intec-cl-border" data-action="forms.call.open">
                                                    <?= Loc::getMessage('C_MAIN_FOOTER_TEMPLATE_1_VIEW_2_FORMS_CALL_BUTTON') ?>
                                                </div>
                                            </div>
                                            <?php include(__DIR__.'/../../parts/forms/call.php') ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arResult['SOCIAL']['SHOW']) { ?>
                                    <!--noindex-->
                                    <div class="widget-part-item widget-social">
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
                                <?php } ?>
                                <?php if ($arResult['LOGOTYPE']['SHOW']) { ?>
                                    <div class="widget-part-item widget-logotype">
                                        <a href="/" class="widget-logotype-wrapper">
                                            <?php include(__DIR__.'/../../parts/logotype.php') ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>