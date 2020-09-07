<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var string $sTemplateId
 */

$arData = $arResult['DATA']['BANNER'];

$sNameTag = $arVisual['BANNER']['HEADER']['H1'] ? 'h1' : 'div';
$sPicture = $arResult['DETAIL_PICTURE'];

if (empty($sPicture))
    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
else
    $sPicture = $sPicture['SRC'];

$bCharacteristics = $arVisual['BANNER']['CHARACTERISTICS']['SHOW'] && !empty($arData['CHARACTERISTICS']);

?>
<div class="catalog-element-main">
    <?php if (!$arVisual['BANNER']['WIDE']) { ?>
        <div class="intec-content">
            <div class="intec-content-wrapper">
    <?php } ?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-element-main-body',
        'style' => [
            'background-image' => 'url("'.$sPicture.'")',
        ],
        'data' => [
            'characteristics' => $bCharacteristics ? 'true' : 'false'
        ]
    ]) ?>
        <div class="intec-content">
            <div class="catalog-element-main-body-wrapper">
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-element-main-content',
                        'intec-grid' => [
                            '',
                            'a-v-center'
                        ]
                    ]
                ]) ?>
                    <div class="intec-grid-item-2 intec-grid-item-768-1">
                        <?php if ($arVisual['BANNER']['BUTTONS']['BACK']['SHOW']) { ?>
                            <div class="catalog-element-main-back">
                                <a href="<?= $arResult['LIST_PAGE_URL'] ?>" class="catalog-element-main-back-button">
                                    <span class="catalog-element-main-back-button-icon"><i class="far fa-angle-left"></i></span>
                                    <span class="catalog-element-main-back-button-text">
                                        <?= Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_BUTTONS_BACK_TEXT') ?>
                                    </span>
                                </a>
                            </div>
                        <?php } ?>
                        <div class="catalog-element-main-text">
                            <?php if ($arVisual['BANNER']['TYPE']['SHOW'] && !empty($arData['TYPE'])) { ?>
                                <div class="catalog-element-main-type">
                                    <?= $arData['TYPE'] ?>
                                </div>
                            <?php } ?>
                            <?= Html::tag($sNameTag, $arResult['NAME'], [
                                'class' => 'catalog-element-main-name'
                            ]) ?>
                            <?php if (!empty($arResult['DETAIL_TEXT'])) { ?>
                                <div class="catalog-element-main-description">
                                    <?= $arResult['DETAIL_TEXT'] ?>
                                </div>
                            <?php } ?>
                            <?php if ($arVisual['BANNER']['PRICE']['SHOW'] && !empty($arData['PRICE']['CURRENT'])) { ?>
                                <div class="catalog-element-main-price-current">
                                    <?= $arData['PRICE']['CURRENT'] ?>
                                </div>
                                <?php if (!empty($arData['PRICE']['OLD'])) { ?>
                                    <div class="catalog-element-main-price-old">
                                        <?= $arData['PRICE']['OLD'] ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($arVisual['BANNER']['ORDER']['SHOW'] && $arResult['FORMS']['ORDER']['USE'] || $arData['BUTTON']['SHOW']) { ?>
                                <div class="catalog-element-main-buttons">
                                    <div class="catalog-element-main-buttons-wrapper">
                                        <?php if ($arVisual['BANNER']['ORDER']['SHOW'] && $arResult['FORMS']['ORDER']['USE']) {

                                            $sOrderText = $arVisual['BANNER']['ORDER']['TEXT'];

                                            if (empty($arVisual['BANNER']['ORDER']['TEXT']))
                                                $sOrderText = Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_ORDER_TEXT_DEFAULT');

                                            $arForm = [
                                                'id' => $arResult['FORMS']['ORDER']['ID'],
                                                'template' => $arResult['FORMS']['TEMPLATE'],
                                                'parameters' => [
                                                    'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'_FORM_ORDER',
                                                    'CONSENT_URL' => $arResult['FORMS']['CONSENT']
                                                ],
                                                'settings' => [
                                                    'title' => !empty($arResult['FORMS']['ORDER']['TITLE']) ?
                                                        $arResult['FORMS']['ORDER']['TITLE'] :
                                                        Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_ORDER_TITLE_DEFAULT')
                                                ],
                                            ];

                                            if (!empty($arResult['FORMS']['ORDER']['FIELD']))
                                                $arForm['fields'] = [
                                                    $arResult['FORMS']['ORDER']['FIELD'] => $arResult['NAME']
                                                ];

                                        ?>
                                            <?= Html::tag('div', $sOrderText, [
                                                'class' => [
                                                    'catalog-element-main-button',
                                                    'intec-cl-background' => [
                                                        '',
                                                        'light-hover'
                                                    ]
                                                ],
                                                'onclick' => '(function() {
                                                    universe.forms.show('.JavaScript::toObject($arForm).');
                                                    if (window.yandex && window.yandex.metrika) {
                                                       window.yandex.metrika.reachGoal(\'forms.open\');
                                                       window.yandex.metrika.reachGoal('.JavaScript::toObject('forms.'.$arForm['id'].'.open').');
                                                   }
                                                })()'
                                            ]) ?>
                                            <?php unset($sOrderText, $arForm) ?>
                                        <?php } ?>
                                        <?php if ($arData['BUTTON']['SHOW']) {

                                            $sButtonText = $arData['BUTTON']['TEXT'];

                                            if (empty($arData['BUTTON']['TEXT']))
                                                $sButtonText = Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_BUTTON_TEXT_DEFAULT');

                                        ?>
                                            <?= Html::tag('a', $sButtonText, [
                                                'class' => [
                                                    'catalog-element-main-button',
                                                    'intec-cl-background' => [
                                                        '',
                                                        'light-hover'
                                                    ]
                                                ],
                                                'href' => $arData['BUTTON']['URL'],
                                                'target' => '_blank'
                                            ]) ?>
                                            <?php unset($sButtonText) ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if ($arVisual['BANNER']['PICTURE']['SHOW'] && !empty($arData['PICTURE'])) {

                        $arPicture = CFile::ResizeImageGet($arData['PICTURE'], [
                            'width' => 1000,
                            'height' => 1000
                        ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                    ?>
                        <div class="catalog-element-main-picture intec-grid-item-2">
                            <?= Html::img($arPicture['src'], [
                                'alt' => $arResult['NAME'],
                                'title' => $arResult['NAME'],
                                'loading' => 'lazy'
                            ]) ?>
                        </div>
                        <?php unset($arPicture) ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
                <?php if ($bCharacteristics) {
                    include(__DIR__.'/banner.characteristics.php');
                } ?>
            </div>
        </div>
    <?= Html::endTag('div') ?>
    <?php if (!$arVisual['BANNER']['WIDE']) { ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php unset($arData, $sPicture, $bCharacteristics) ?>