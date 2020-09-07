<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$sTemplateId = $arData['id'];
$sPrefix = 'BANNER_';
$arParameters = [];

foreach ($arParams as $sKey => $sValue)
    if (StringHelper::startsWith($sKey, $sPrefix)) {
        $sKey = StringHelper::cut($sKey, StringHelper::length($sPrefix));
        $arParameters[$sKey] = $sValue;
    }

$arParameters['SELECTOR'] = '#'.$sTemplateId;
$arParameters['ATTRIBUTE'] = 'data-color';

?>
<div class="widget-banner-1">
    <?php $APPLICATION->IncludeComponent(
        'intec.universe:main.slider',
        'template.1',
        $arParameters,
        $this->getComponent()
    ) ?>
</div>
<?php if ($arResult['VISUAL']['TRANSPARENCY'] && !defined('EDITOR')) { ?>
    <script type="text/javascript">
        (function ($, api) {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var header = $('.widget-view.widget-view-desktop', root);
            var banner = $('.widget-banner', root);
            var slides = $('.widget-item-content', banner);
            var adapt;

            adapt = function () {
                var height;

                if (header.css('display') === 'none') {
                    height = 0;
                } else {
                    height = header.height();
                }

                banner.css({'margin-top': -height + 'px'});
                slides.css({'padding-top': height + 'px'});
            };

            $(window).on('resize', adapt);
            $(document).on('ready', adapt);

            adapt();
        })(jQuery, intec);
    </script>
<?php } ?>