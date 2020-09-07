<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var array $arVisual
 */

?>
<!--noindex-->
<div class="catalog-element-section-reviews">
</div>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);

        universe.components.get(<?= JavaScript::toObject([
            'component' => 'intec.universe:reviews',
            'template' => '.default',
            'parameters' => [
                'IBLOCK_TYPE' => $arParams['REVIEWS_IBLOCK_TYPE'],
                'IBLOCK_ID' => $arParams['REVIEWS_IBLOCK_ID'],
                'ELEMENT_ID' => $arResult['ID'],
                'DISPLAY_REVIEWS_COUNT' => 5,
                'PROPERTY_ELEMENT_ID' => $arParams['REVIEWS_PROPERTY_ELEMENT_ID'],
                'MAIL_EVENT' => $arParams['REVIEWS_MAIL_EVENT'],
                'USE_CAPTCHA' => $arParams['REVIEWS_USE_CAPTCHA'],
                'AJAX_MODE' => 'Y',
                'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'-reviews',
                'AJAX_OPTION_SHADOW' => 'N',
                'AJAX_OPTION_JUMP' => 'Y',
                'AJAX_OPTION_STYLE' => 'Y',
                'ITEM_NAME' => $arResult['NAME']
            ]
        ]) ?>, function (content) {
            $('.catalog-element-section-reviews', root).html(content);
        });
    })(jQuery, intec);
</script>
<!--/noindex-->