<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
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

$iCounter = 0;

?>
<div class="widget c-advantages c-advantages-template-24" id="<?= $sTemplateId ?>" data-collapsed="true" data-hiding="false">
    <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
        <div class="widget-header">
            <div class="intec-content">
                <div class="intec-content-wrapper">
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
            </div>
        </div>
    <?php } ?>
    <div class="widget-content">
        <?= Html::beginTag('div', [
            'class' => [
                'widget-items'
            ],
            'data' => [
                'collapsed' => 'true',
                'role' => 'items'
            ]
        ]) ?>
            <?php foreach ($arResult['ITEMS'] as $arItem) {

                $sId = $sTemplateId.'_'.$arItem['ID'];
                $sAreaId = $this->GetEditAreaId($sId);
                $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                $iCounter++;
            ?>
                <?= Html::beginTag('div', [
                    'id' => $sAreaId,
                    'class' => [
                        'widget-item'
                    ],
                    'data' => [
                        'role' => 'item',
                        'hidden' => 'false',
                        'color' => $iCounter % 2 == 0 ? 'dark' : null
                    ]
                ]) ?>
                    <div class="intec-content">
                        <div class="intec-content-wrapper">
                            <div class="widget-item-wrapper">
                                <div class="widget-item-text">
                                    <div class="widget-item-name">
                                        <?= $arItem['NAME'] ?>
                                    </div>
                                    <?php if ($arVisual['CATEGORY']['SHOW']) { ?>
                                        <div class="widget-item-category-wrap">
                                            <div class="widget-item-category">
                                                <?= $arItem['DATA']['CATEGORY']['VALUE'] ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($arItem['PREVIEW_TEXT']) && $arVisual['PREVIEW']['SHOW']) { ?>
                                        <div class="widget-item-description">
                                            <?= $arItem['PREVIEW_TEXT'] ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arVisual['PICTURES']['SHOW'] && !empty($arItem['DATA']['PICTURES'])) { ?>
                                        <div class="widget-item-images-wrap">
                                            <div class="widget-item-images intec-grid intec-grid-wrap intec-grid-i-h-20 intec-grid-i-v-25">
                                                <?php foreach ($arItem['DATA']['PICTURES'] as $arImage) { ?>
                                                    <div class="widget-item-item intec-grid-item-3 intec-grid-item-1024-2 intec-grid-item-768-1">
                                                        <div class="widget-item-image-wrapper">
                                                            <?= Html::tag('div', '', [
                                                                'href' => $arItem['SECTION_PAGE_URL'],
                                                                'class' => [
                                                                    'widget-item-image'
                                                                ],
                                                                'data' => [
                                                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $arImage['PICTURE'] : null
                                                                ],
                                                                'style' => [
                                                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$arImage['PICTURE'].'\')' : null
                                                                ]
                                                            ]) ?>
                                                            <div class="widget-item-image-title">
                                                                <?= $arImage['TEXT']['TITLE'] ?>
                                                            </div>
                                                            <div class="widget-item-image-description">
                                                                <?= $arImage['TEXT']['DESCRIPTION'] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?= Html::endTag('div') ?>
            <?php } ?>
        <?= Html::endTag('div') ?>
    </div>
    <?php if ($arVisual['HIDING']['USE']) { ?>
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="widget-buttons">
                    <div class="<?= Html::cssClassFromArray([
                        'widget-button',
                        'widget-button-show',
                        'intec-ui' => [
                            '',
                            'control-button',
                            'mod-transparent',
                            'mod-round-half',
                            'scheme-current',
                            'size-5'
                        ]
                    ]) ?>" data-role="button">
                        <?= Loc::getMessage('C_MAIN_STAFF_TEMPLATE_2_BUTTONS_SHOW') ?>
                    </div>
                    <div class="<?= Html::cssClassFromArray([
                        'widget-button',
                        'widget-button-hide',
                        'intec-ui' => [
                            '',
                            'control-button',
                            'mod-transparent',
                            'mod-round-half',
                            'scheme-current',
                            'size-5'
                        ]
                    ]) ?>" data-role="button">
                        <?= Loc::getMessage('C_MAIN_STAFF_TEMPLATE_2_BUTTONS_HIDE') ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($arVisual['HIDING']['USE']) { ?>
        <script type="text/javascript">
            (function ($, api) {
                var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                var rows = <?= JavaScript::toObject($arVisual['HIDING']['VISIBLE']) ?>;
                var button = $('[data-role="button"]', root);
                var items = $('[data-role="items"] [data-role="item"]', root);
                var state = true;

                root.getColumns = function () {
                    return Math.round(items.container.width() / items.outerWidth());
                };

                root.getVisible = function () {
                    return rows * root.getColumns();
                };

                root.refresh = function () {
                    root.attr('data-hiding', root.getVisible() <= items.size() ? 'true' : 'false');
                    root.attr('data-collapsed', state ? 'true' : 'false');
                    items.refresh(state);
                };

                items.container = $('[data-role="items"]', root);
                items.show = function (animate) {
                    if (state)
                        return;

                    var container = items.container.stop();

                    state = !state;

                    if (animate) {
                        var height = {
                            'current': container.height(),
                            'target': 0
                        };

                        root.refresh();
                        height.target = container.height();
                        container.css({
                            'height': height.current
                        }).animate({
                            'height': height.target
                        }, 500, function () {
                            container.css({'height': ''});
                        });
                    } else {
                        root.refresh();
                    }
                };

                items.toggle = function (animate) {
                    if (state) {
                        items.hide(animate);
                    } else {
                        items.show(animate);
                    }
                };

                items.refresh = function (state) {
                    var count = root.getVisible();
                    var counter = 0;

                    items.container.attr('data-collapsed', state ? 'true' : 'false');
                    items.each(function () {
                        var item = $(this);

                        item.attr('data-hidden', 'false');
                        counter++;

                        if (!state && (counter > count))
                            item.attr('data-hidden', 'true');
                    })
                };

                items.hide = function (animate) {
                    if (!state)
                        return;

                    var container = items.container.stop();

                    state = !state;

                    if (animate) {
                        var height = {
                            'current': container.height(),
                            'target': 0
                        };

                        items.refresh(false);
                        height.target = container.height();
                        items.refresh(true);
                        container.css({
                            'height': height.current
                        }).animate({
                            'height': height.target
                        }, 500, function () {
                            container.css({'height': ''});
                            root.refresh();
                        });
                    } else {
                        root.refresh();
                    }
                };

                button.on('click', function () {
                    items.toggle(true);
                });

                $(window).on('resize', root.refresh);
                items.toggle(false);
            })(jQuery, intec)
        </script>
    <?php } ?>
</div>