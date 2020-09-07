<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var string $sTemplateId
 */

?>
<div class="widget-contacts-container intec-grid-item-auto">
    <?= Html::beginTag('div', [
        'class' => 'widget-contacts',
        'data' => [
            'block' => 'phone',
            'multiple' => !empty($arResult['CONTACTS']['VALUES']) ? 'true' : 'false',
            'advanced' => $arResult['CONTACTS']['ADVANCED'] ? 'true' : 'false',
            'expanded' => 'false'
        ]
    ]) ?>
        <div class="widget-contacts-main" data-block-action="popup.open">
            <?php if ($arResult['CONTACTS']['ADVANCED']) { ?>
                <?php if (!empty($arResult['CONTACTS']['SELECTED']['PHONE']['DISPLAY'])) { ?>
                    <?= Html::tag('a', $arResult['CONTACTS']['SELECTED']['PHONE']['DISPLAY'], [
                        'href' => 'tel:'.$arResult['CONTACTS']['SELECTED']['PHONE']['VALUE']
                    ]) ?>
                <?php } ?>
            <?php } else { ?>
                <?= Html::tag('a', $arResult['CONTACTS']['SELECTED']['DISPLAY'], [
                    'href' => 'tel:'.$arResult['CONTACTS']['SELECTED']['VALUE']
                ]) ?>
            <?php } ?>
            <?php if (!empty($arResult['CONTACTS']['VALUES'])) { ?>
                <?= FileHelper::getFileData(__DIR__.'/../svg/contacts.arrow.svg') ?>
            <?php } ?>
        </div>
        <?php if (!empty($arResult['CONTACTS']['VALUES'])) { ?>
            <div class="widget-contacts-advanced" data-block-element="popup">
                <div class="widget-contacts-advanced-items-wrap">
                    <div class="widget-contacts-advanced-items">
                        <?php if ($arResult['CONTACTS']['ADVANCED']) { ?>
                            <?php foreach ($arResult['CONTACTS']['VALUES'] as $arContact) { ?>
                                <div class="widget-contacts-advanced-item">
                                    <?php if (!empty($arContact['PHONE']['DISPLAY'])) { ?>
                                        <div class="widget-contacts-advanced-item-phone">
                                            <?= Html::tag('a', $arContact['PHONE']['DISPLAY'], [
                                                'href' => 'tel:'.$arContact['PHONE']['VALUE']
                                            ]) ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arContact['ADDRESS']) { ?>
                                        <div class="widget-contacts-advanced-item-address">
                                            <?= $arContact['ADDRESS'] ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($arContact['SCHEDULE'])) { ?>
                                        <div class="widget-contacts-advanced-item-schedule">
                                            <?php if (Type::isArray($arContact['SCHEDULE'])) { ?>
                                                <?php foreach ($arContact['SCHEDULE'] as $sSchedule) { ?>
                                                    <div class="widget-contacts-advanced-item-schedule-item">
                                                        <?= $sSchedule ?>
                                                    </div>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div class="widget-contacts-advanced-item-schedule-item">
                                                    <?= $arContact['SCHEDULE'] ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($arContact['EMAIL'])) { ?>
                                        <div class="widget-contacts-advanced-item-email">
                                            <?= Html::tag('a', $arContact['EMAIL'], [
                                                'class' => [
                                                    'intec-cl-text',
                                                    'intec-cl-text-light-hover'
                                                ],
                                                'href' => 'mailto:'.$arContact['EMAIL']
                                            ]) ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <?php foreach ($arResult['CONTACTS']['VALUES'] as $arContact) { ?>
                                <div class="widget-contacts-advanced-item">
                                    <div class="widget-contacts-advanced-item-phone">
                                        <?= Html::tag('a', $arContact['DISPLAY'], [
                                            'href' => 'tel:'.$arContact['VALUE']
                                        ]) ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?= Html::endTag('div') ?>
    <script type="text/javascript">
        (function ($) {
            $(document).on('ready', function () {
                var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                var block = $('[data-block="phone"]', root);
                var popup = $('[data-block-element="popup"]', block);

                popup.open = $('[data-block-action="popup.open"]', block);
                popup.open.on('mouseenter', function () {
                    block.attr('data-expanded', 'true');
                });

                block.on('mouseleave', function () {
                    block.attr('data-expanded', 'false');
                });
            });
        })(jQuery)
    </script>
</div>