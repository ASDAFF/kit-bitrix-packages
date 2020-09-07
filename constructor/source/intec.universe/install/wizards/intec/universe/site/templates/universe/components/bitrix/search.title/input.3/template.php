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
<div id="<?= $sTemplateId ?>" class="ns-bitrix c-search-title c-search-title-input-3">
    <div class="search-title">
        <form action="<?= $arResult["FORM_ACTION"] ?>" class="search-title-form">
            <?= Html::beginTag('div', [
                'class' => [
                    'search-title-form-wrapper',
                    'intec-grid' => [
                        '',
                        'i-h-5',
                        'nowrap',
                        'a-v-center'
                    ]
                ]
            ]) ?>
                <div class="intec-grid-item">
                    <?= Html::textInput('q', null, [
                        'class' => [
                            'search-title-input'
                        ],
                        'id' => $arResult['INPUT']['ID'],
                        'maxlength' => 50,
                        'autocomplete' => 'off',
                        'placeholder' => Loc::getMessage('C_SEARCH_TITLE_INPUT_1_PLACEHOLDER')
                    ]) ?>
                </div>
                <div class="intec-grid-item-auto">
                    <button type="submit" class="search-title-button" aria-hidden="true">
                        <i class="glyph-icon-loop"></i>
                    </button>
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
</div>