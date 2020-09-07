<?php if (defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED === true);

use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var string $sTemplateContainer
 * @var array $arVisual
 * @var array $arNavigation
 */

$oSigner = new \Bitrix\Main\Security\Sign\Signer;
$sSignedTemplate = $oSigner->sign($templateName, 'catalog.section');
$sSignedParameters = $oSigner->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'catalog.section');

?>
<script type="text/javascript">
    (function ($, api) {
        <?php if ($arVisual['TABS']['USE'] && !empty($arResult['TABS'])) { ?>
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var tabs = $('[data-role="tabs.item"]', root);
            var items = $('[data-role="tabs.content.item"]', root);

            tabs.each(function () {
                var self = $(this);
                var id = self.attr('data-id');

                self.on('click', function () {
                    var active = self.attr('data-active') === 'true';

                    if (!active) {
                        tabs.attr('data-active', 'false')
                            .removeClass('intec-cl-background')
                            .css('pointer-events', 'none');

                        self.attr('data-active', 'true')
                            .addClass('intec-cl-background');

                        if (id !== 'all') {
                            self.itemsShow = items.filter(function () {
                                var item = $(this);
                                var data = item.data('tabs');

                                if (data.hasOwnProperty(id))
                                    return item;
                            });
                        } else {
                            self.itemsShow = items;
                        }

                        self.itemsHide = items.filter('[data-active="true"]');
                        self.itemsHide.attr('data-active', 'false');

                        setTimeout(function () {
                            self.itemsHide.css('display', 'none');
                            self.itemsShow.css('display', '');

                            setTimeout(function () {
                                self.itemsShow.attr('data-active', 'true');

                                setTimeout(function () {
                                    tabs.css('pointer-events', '');
                                }, 300);
                            }, 5);
                        }, 305);
                    }
                });
            });
        <?php } ?>
        var component;

        BX.message(<?= JavaScript::toObject([
            'BTN_MESSAGE_LAZY_LOAD' => '',
            'BTN_MESSAGE_LAZY_LOAD_WAITER' => ''
        ]) ?>);

        component = new JCCatalogSectionComponent(<?= JavaScript::toObject([
            'siteId' => SITE_ID,
            'componentPath' => $componentPath,
            'navParams' => $arNavigation,
            'deferredLoad' => false,
            'initiallyShowHeader' => false,
            'bigData' => $arResult['BIG_DATA'],
            'lazyLoad' => $arVisual['NAVIGATION']['LAZY']['BUTTON'],
            'loadOnScroll' => $arVisual['NAVIGATION']['LAZY']['SCROLL'],
            'template' => $sSignedTemplate,
            'parameters' => $sSignedParameters,
            'ajaxId' => $arParams['AJAX_ID'],
            'container' => $sTemplateContainer
        ]) ?>);
    })(jQuery, intec);
</script>
<?php unset($sSignedParameters, $sSignedTemplate, $oSigner);