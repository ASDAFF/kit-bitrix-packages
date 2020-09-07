<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var string $sTemplateId
 * @var array $arVisual
 */

?>
<?php $vItems = function ($arItems) use ($arVisual, $sTemplateId) { ?>
    <?php if (empty($arItems)) return ?>
    <?php $iCounter = 0 ?>
    <?php foreach ($arItems as $arItem) {

        $sId = $sTemplateId.'_'.$arItem['ID'];
        $sAreaId = $this->GetEditAreaId($sId);
        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

        $iCounter++;

        $arData = $arItem['DATA'];

        if ($arData['BACKGROUND']['TYPE'] === 'image') {
            $sBackground = 'url("' . $arData['BACKGROUND']['IMAGE']['SRC'] . '")';
        } else if ($arData['BACKGROUND']['TYPE'] === 'color') {
            $sBackground = $arData['BACKGROUND']['COLOR'];
        }

        if (empty($sBackground)) {
            $sBackground = '#17171d';
            $arData['THEME'] = 'white';
        }

    ?>
        <?= Html::beginTag('div', [
            'id' => $sAreaId,
            'class' => 'catalog-section-item',
            'style' => [
                'background' => $sBackground
            ],
            'data' => [
                'entity' => 'items-row',
                'role' => 'tabs.content.item',
                'tabs' => Json::encode($arData['TABS'], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true),
                'active' => 'true',
                'scheme' => $arData['THEME'],
                'side' => $iCounter % 2 === 0 ? 'right' : 'left'
            ]
        ]) ?>
            <div class="intec-content intec-content-visible">
                <div class="catalog-section-item-wrapper">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-section-item-content',
                            'intec-grid' => [
                                '',
                                'i-15',
                                '1024-wrap'
                            ]
                        ]
                    ]) ?>
                        <?php if ($arVisual['IMAGE']['SHOW'] && !empty($arData['IMAGE'])) {

                            $arPicture = CFile::ResizeImageGet($arData['IMAGE'], [
                                'width' => 900,
                                'height' => 900
                            ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                        ?>
                            <div class="intec-grid-item intec-grid-item-1024-1">
                                <div class="catalog-section-item-image">
                                    <img loading="lazy" src="<?= $arPicture['src'] ?>" alt="">
                                </div>
                                <?php if ($arVisual['MARKS']['SHOW'] && !empty($arData['MARKS'])) { ?>
                                    <div class="catalog-section-item-marks" data-align="center">
                                        <div class="catalog-section-item-marks-name">
                                            <?= $arData['MARKS']['NAME'].':' ?>
                                        </div>
                                        <div class="catalog-section-item-marks-items">
                                            <?php foreach ($arData['MARKS']['VALUE'] as $sMarks) { ?>
                                                <div class="catalog-section-item-marks-item">
                                                    <?= $sMarks ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div class="intec-grid-item intec-grid-item-1024-1">
                            <?php if ($arVisual['TYPE']['SHOW'] && !empty($arData['TYPE'])) { ?>
                                <div class="catalog-section-item-type">
                                    <?= $arData['TYPE'] ?>
                                </div>
                            <?php } ?>
                            <div class="intec-grid intec-grid-i-10 intec-grid-a-v-center">
                                <?php if ($arVisual['LOGOTYPE']['SHOW'] && !empty($arData['LOGOTYPE'])) {

                                    $arPicture = CFile::ResizeImageGet($arData['LOGOTYPE'], [
                                        'width' => 135,
                                        'height' => 135
                                    ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                                ?>
                                    <div class="catalog-section-item-logotype intec-grid-item-auto">
                                        <img loading="lazy" src="<?= $arPicture['src'] ?>" alt="<?= Html::encode($arItem['NAME']) ?>" title="<?= Html::encode($arItem['NAME']) ?>">
                                    </div>
                                <?php } ?>
                                <div class="intec-grid-item">
                                    <?= Html::tag('a', $arItem['NAME'], [
                                        'href' => $arItem['DETAIL_PAGE_URL'],
                                        'class' => 'catalog-section-item-name'
                                    ]) ?>
                                </div>
                            </div>
                            <?php if ($arVisual['PREVIEW']['SHOW'] && !empty($arItem['PREVIEW_TEXT'])) { ?>
                                <div class="catalog-section-item-description">
                                    <?= $arItem['PREVIEW_TEXT'] ?>
                                </div>
                            <?php } ?>
                            <?php if ($arVisual['PRICE']['SHOW']['CURRENT'] && !empty($arData['PRICE']['CURRENT'])) { ?>
                                <div class="catalog-section-item-price">
                                    <div class="catalog-section-item-price-current">
                                        <?= $arData['PRICE']['CURRENT'] ?>
                                    </div>
                                    <?php if ($arVisual['PRICE']['SHOW']['OLD'] && !empty($arData['PRICE']['OLD'])) { ?>
                                        <div class="catalog-section-item-price-old">
                                            <?= $arData['PRICE']['OLD'] ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <div class="catalog-section-item-buttons">
                                <div class="catalog-section-item-buttons-wrap">
                                    <?= Html::tag('a', Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_TEMPLATE_DETAIL_BUTTON_TEXT'), [
                                        'href' => $arItem['DETAIL_PAGE_URL'],
                                        'class' => [
                                            'catalog-section-item-button',
                                            'intec-cl-border-hover',
                                            'intec-cl-background-hover'
                                        ]
                                    ]) ?>
                                    <?php if ($arData['BUTTON']['SHOW']) {

                                        if (!empty($arData['BUTTON']['TEXT']))
                                            $sButtonText = $arData['BUTTON']['TEXT'];
                                        else
                                            $sButtonText = Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_TEMPLATE_ADDITIONAL_BUTTON_TEXT');

                                    ?>
                                        <?= Html::tag('a', $sButtonText, [
                                            'href' => $arData['BUTTON']['URL'],
                                            'target' => '_blank',
                                            'class' => [
                                                'catalog-section-item-button',
                                                'catalog-section-item-button-additional',
                                                'intec-cl-border-hover',
                                                'intec-cl-background-hover'
                                            ]
                                        ]) ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if ((!$arVisual['IMAGE']['SHOW'] || empty($arData['IMAGE'])) && $arVisual['MARKS']['SHOW'] && !empty($arData['MARKS'])) { ?>
                                <div class="catalog-section-item-marks" data-align="left">
                                    <div class="catalog-section-item-marks-name">
                                        <?= $arData['MARKS']['NAME'].':' ?>
                                    </div>
                                    <div class="catalog-section-item-marks-items">
                                        <?php foreach ($arData['MARKS']['VALUE'] as $sMarks) { ?>
                                            <div class="catalog-section-item-marks-item">
                                                <?= $sMarks ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?= Html::endTag('div') ?>
                </div>
            </div>
        <?= Html::endTag('div') ?>
    <?php } ?>
<?php } ?>