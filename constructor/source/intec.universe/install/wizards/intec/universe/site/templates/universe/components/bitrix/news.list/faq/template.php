<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Html;

/** @var array $arParams
  * @var array $arResult
  * @global CMain $APPLICATION
  * @global CUser $USER
  * @global CDatabase $DB
  * @var CBitrixComponentTemplate $this
  * @var string $templateName
  * @var string $templateFile
  * @var string $templateFolder
  * @var string $componentPath
  * @var CBitrixComponent $component
  */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
?>
<div class="faq" id="<?= $sTemplateId ?>">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <ul class="nav nav-tabs intec-tabs intec-ui-mod-simple">
                <?php $bSectionFirst = true ?>
                <?php foreach($arResult['SECTIONS'] as $arSection) { ?>
                    <?php if (count($arSection['ITEMS']) <= 0) continue; ?>
                    <li role="presentation"<?= $bSectionFirst ? ' class="active"' : null ?>>
                        <a href="#<?= $sTemplateId ?>-section-<?= $arSection['ID'] ?>"
                           aria-controls="<?= $sTemplateId ?>-section-<?= $arSection['ID'] ?>"
                           role="tab"
                           data-toggle="tab"
                        ><?= $arSection['NAME'] ?></a>
                    </li>
                    <?php $bSectionFirst = false ?>
                <?php } ?>
            </ul>
            <div class="tab-content clearfix">
                <?php $bSectionFirst = true ?>
                <?php foreach($arResult['SECTIONS'] as $arSection) { ?>
                    <?php if (count($arSection['ITEMS']) <= 0) continue; ?>
                    <div role="tabpanel"
                         id="<?= $sTemplateId ?>-section-<?= $arSection['ID'] ?>"
                         class="tab-pane<?= $bSectionFirst ? ' active' : null ?>"
                    >
                        <div class="faq-section">
                            <?php $bItemFirst = true ?>
                            <?php foreach ($arSection['ITEMS'] as $arItem) { ?>
                            <?php
                                $sId = $sTemplateId.'_'.$arItem['ID'];
                                $sAreaId = $this->GetEditAreaId($sId);
                                $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                                $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);
                            ?>
                                <?php if (!$bItemFirst) { ?>
                                    <div class="faq-delimiter"></div>
                                <?php } ?>
                                <div class="faq-item" id="<?= $sAreaId ?>" itemscope="" itemtype="http://schema.org/Question">
                                    <div class="faq-item-wrapper">
                                        <div class="faq-item-name" data-action="toggle">
                                            <div class="faq-item-name-text" itemprop="name"><?= $arItem['NAME'] ?></div>
                                            <div class="faq-item-name-indicators">
                                                <div class="intec-aligner"></div>
                                                <i class="fa fa-chevron-up faq-item-name-indicator faq-item-name-indicator-active"></i>
                                                <i class="fa fa-chevron-down faq-item-name-indicator faq-item-name-indicator-inactive"></i>
                                            </div>
                                        </div>
                                        <div class="faq-item-description" itemprop="acceptedAnswer" itemscope="" itemtype="http://schema.org/Answer">
                                            <div class="faq-item-description-wrapper" itemprop="text"><?= $arItem['PREVIEW_TEXT'] ?></div>
                                        </div>
                                    </div>
                                </div>
                                <?php $bItemFirst = false ?>
                            <?php } ?>
                        </div>
                    </div>
                    <script type="text/javascript">
                        (function ($, api) {
                            var root = $(<?= JavaScript::toObject('#'.$sTemplateId.' #'.$sTemplateId.'-section-'.$arSection['ID']) ?>);
                            var items = root.find('.faq-item');
                            var active = null;
                            var duration = 300;

                            items.each(function () {
                                var self = this;
                                var item = $(this);
                                var toggle = item.find('[data-action=toggle]');

                                toggle.on('click', function () {
                                    if (active === self) {
                                        close(self);
                                        active = null;
                                    } else {
                                        open(self);
                                    }
                                });
                            });

                            var open = function (item) {
                                if (active === item)
                                    return;

                                var block;
                                var height;

                                close(active);
                                active = item;

                                item = $(item);
                                item.addClass('active');
                                block = item.find('.faq-item-description');
                                height = block.css({
                                    'display': 'block',
                                    'height': 'auto'
                                }).height();
                                block.css({'height': 0}).stop().animate({'height': height + 'px'}, duration, function () {
                                    block.css('height', 'auto');
                                });
                            };

                            var close = function (item) {
                                var block;

                                item = $(item);
                                item.removeClass('active');
                                block = item.find('.faq-item-description');
                                block.stop().animate({'height': 0}, duration, function () {
                                    block.css({
                                        'display': 'none',
                                        'height': 'auto'
                                    });
                                });
                            };
                        })(jQuery, intec);
                    </script>
                    <?php $bSectionFirst = false ?>
                <? } ?>
            </div>
        </div>
    </div>
</div>
