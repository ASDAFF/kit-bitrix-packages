<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die() ?>
<?php

use intec\core\bitrix\Component;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<div id="<?= $sTemplateId ?>" class="ns-bitrix c-search-title c-search-title-input-2">
    <div class="search-title">
        <form action="<?= $arResult["FORM_ACTION"] ?>" class="search-title-form" data-role="area" data-show="false">
            <?= Html::beginTag('div', [
                'class' => [
                    'search-title-form-wrapper',
                    'intec-grid' => [
                        '',
                        'nowrap',
                        'a-v-center'
                    ]
                ]
            ]) ?>
                <div class="intec-grid-item">
                    <div class="search-title-input-wrap">
                        <?= Html::textInput('q', null, [
                            'class' => [
                                'search-title-input'
                            ],
                            'id' => $arResult['INPUT']['ID'],
                            'maxlength' => 50,
                            'autocomplete' => 'off',
                            'data-role' => 'input',
                            'placeholder' => Loc::getMessage('C_SEARCH_TITLE_INPUT_1_PLACEHOLDER')
                        ]) ?>
                    </div>
                </div>
                <div class="intec-grid-item-auto">
                    <div class="search-title-button-wrap">
                        <div type="submit" class="search-title-button intec-cl-text" data-role="icon">
                            <i class="glyph-icon-loop"></i>
                        </div>
                        <button type="submit" class="search-title-button intec-cl-text" aria-hidden="true" data-role="button">
                            <i class="glyph-icon-loop"></i>
                        </button>
                    </div>
                </div>
            <?= Html::endTag('div') ?>
        </form>
    </div>
    <?php if ($arResult['TIPS']['USE']) { ?>
        <script type="text/javascript">
            BX.ready(function(){
                new JCTitleSearch(<?= JavaScript::toObject([
                    'AJAX_PAGE' => POST_FORM_ACTION_URI,
                    'CONTAINER_ID' => $sTemplateId,
                    'INPUT_ID' => $arResult['INPUT']['ID'],
                    'MIN_QUERY_LEN' => 2
                ]) ?>);
            });
        </script>
    <?php } ?>

    <script type="text/javascript">
        (function ($, api) {
            $(document).on('ready', function () {
                var root = $(<?= JavaScript::toObject('#' . $sTemplateId) ?>);
                var button = $('[data-role="button"]', root);
                var input = $('[data-role="input"]', root);
                var icon = $('[data-role="icon"]', root);
                var area = $('[data-role="area"]', root);

                area.open = function(){
                    area.attr('data-show', 'true');
                };

                area.close = function(){
                    area.attr('data-show', 'false');
                };

                $(document).click(function(e){
                    if(e.target!=area[0]&&!area.has(e.target).length){
                        area.close();
                    }
                });

                input.on('input', function(e){
                    if (input.val().length > 0) {
                        button.show();
                        icon.hide();
                    } else {
                        button.hide();
                        icon.show();
                    }
                });

                icon.on('click', function() {
                    var show = area.attr('data-show');
                    if (show == "false") {
                        area.open();
                    } else {
                        area.close();
                    }
                });
            });
        })(jQuery, intec);
    </script>
</div>