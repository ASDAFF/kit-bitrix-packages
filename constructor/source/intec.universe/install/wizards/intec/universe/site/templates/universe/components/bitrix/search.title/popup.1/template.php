<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<div id="<?= $sTemplateId ?>" class="ns-bitrix c-search-title c-search-title-popup-1">
    <div class="search-title-button intec-cl-text-hover" data-action="search.open">
        <div class="search-title-button-wrapper intec-grid intec-grid-nowrap intec-grid-i-h-5 intec-grid-a-v-center">
            <div class="search-title-button-icon-wrap intec-grid-item-auto">
                <div class="search-title-button-icon">
                    <i class="glyph-icon-loop"></i>
                </div>
            </div>
            <div class="search-title-button-text-wrap intec-grid-item-auto">
                <div class="search-title-button-text">
                    <?= Loc::getMessage('C_SEARCH_TITLE_POPUP_1_BUTTON') ?>
                </div>
            </div>
        </div>
    </div>
    <div class="search-title intec-content-wrap" data-role="search">
        <div class="search-title-overlay" data-role="overlay" data-action="search.close"></div>
        <div class="search-title-wrapper" data-role="panel">
            <div class="search-title-wrapper-2 intec-content intec-content-primary intec-content-visible">
                <div class="search-title-wrapper-3 intec-content-wrapper">
                    <div class="search-title-wrapper-4">
                        <form action="<?= $arResult["FORM_ACTION"] ?>" class="search-title-form">
                            <?= Html::beginTag('div', [
                                'class' => [
                                    'search-title-form-wrapper',
                                    'intec-grid' => [
                                        '',
                                        'i-h-10',
                                        'nowrap',
                                        'a-v-center'
                                    ]
                                ]
                            ]) ?>
                                <div class="intec-grid-item-auto">
                                    <button type="submit" class="search-title-form-button" aria-hidden="true">
                                        <i class="glyph-icon-loop"></i>
                                    </button>
                                </div>
                                <div class="intec-grid-item">
                                    <?= Html::textInput('q', null, [
                                        'class' => [
                                            'search-title-form-input'
                                        ],
                                        'id' => $arResult['INPUT']['ID'],
                                        'maxlength' => 50,
                                        'autocomplete' => 'off',
                                        'placeholder' => Loc::getMessage('C_SEARCH_TITLE_POPUP_1_PLACEHOLDER'),
                                        'data' => [
                                            'role' => 'input'
                                        ]
                                    ]) ?>
                                </div>
                                <div class="intec-grid-item-auto">
                                    <div class="search-title-form-button" data-action="search.close" aria-hidden="true">
                                        <i class="far fa-times"></i>
                                    </div>
                                </div>
                            <?= Html::endTag('div') ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        (function ($, api) {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var search = $('[data-role="search"]', root);
            var overlay = $('[data-role="overlay"]', search);
            var panel = $('[data-role="panel"]', search);
            var input = $('[data-role="input"]', search);
            var page = $('html');
            var buttons = {};
            var state = false;

            buttons.open = $('[data-action="search.open"]', root);
            buttons.close = $('[data-action="search.close"]', root);

            search.open = function () {
                if (state) return;

                state = true;
                search.attr('data-expanded', 'true').css({
                    'display': 'block'
                });

                page.css({
                    'overflow': 'hidden',
                    'height': '100%'
                });

                panel.stop().animate({'top': 0}, 350);
                overlay.stop().animate({'opacity': 0.5}, 350);

                input.focus();
            };

            search.close = function () {
                if (!state) return;

                state = false;
                search.attr('data-expanded', 'false');

                panel.stop().animate({'top': -panel.height()}, 350);
                overlay.stop().animate({'opacity': 0}, 350, function () {
                    search.css({'display': 'none'});
                    input.blur();

                    page.css({
                        'overflow': '',
                        'height': ''
                    });
                });
            };

            buttons.open.on('click', search.open);
            buttons.close.on('click', search.close);

            <?php if ($arResult['TIPS']['USE']) { ?>
                BX.ready(function () {
                    var control = new JCTitleSearch(<?= JavaScript::toObject([
                        'AJAX_PAGE' => POST_FORM_ACTION_URI,
                        'CONTAINER_ID' => $sTemplateId,
                        'INPUT_ID' => $arResult['INPUT']['ID'],
                        'MIN_QUERY_LEN' => 2
                    ]) ?>);

                    control.ShowResult = (function () {
                        var handler = control.ShowResult;

                        return function () {
                            if (state) handler.apply(control, arguments);
                        }
                    })();
                });
            <?php } ?>
        })(jQuery, intec);
    </script>
</div>