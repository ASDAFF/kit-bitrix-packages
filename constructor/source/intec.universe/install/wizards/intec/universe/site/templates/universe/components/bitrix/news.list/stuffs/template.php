<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
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

$arVisual = $arResult['VISUAL'];
?>
<div class="stuffs" id="<?= $sTemplateId ?>">
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
                        <div class="stuffs-section">
                            <div class="stuffs-section-wrapper">
                                <?php foreach ($arSection['ITEMS'] as $arItem) { ?>
                                <?php
                                    $sId = $sTemplateId.'_'.$sType.'_tile_'.$arItem['ID'];
                                    $sAreaId = $this->GetEditAreaId($sId);
                                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                                    $sPicture = null;

                                    if (!empty($arItem['PREVIEW_PICTURE'])) {
                                        $sPicture = $arItem['PREVIEW_PICTURE'];
                                    } else if (!empty($arItem['DETAIL_PICTURE'])) {
                                        $sPicture = $arItem['DETAIL_PICTURE'];
                                    }

                                    $sPicture = CFile::ResizeImageGet($sPicture, array(
                                        'width' => 400,
                                        'height' => 400
                                    ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                                    if (!empty($sPicture)) {
                                        $sPicture = $sPicture['src'];
                                    } else {
                                        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                                    }

                                    $sPosition = ArrayHelper::getValue($arItem, ['SYSTEM_PROPERTIES', 'POSITION', 'VALUE']);
                                    $sPhone = ArrayHelper::getValue($arItem, ['SYSTEM_PROPERTIES', 'PHONE', 'VALUE']);
                                    $sSkype = ArrayHelper::getValue($arItem, ['SYSTEM_PROPERTIES', 'SKYPE', 'VALUE']);
                                    $sEmail = ArrayHelper::getValue($arItem, ['SYSTEM_PROPERTIES', 'EMAIL', 'VALUE']);
                                ?>
                                    <div class="stuffs-item">
                                        <div class="stuffs-item-wrapper" id="<?= $sAreaId ?>">
                                            <?= Html::tag('div', '', [
                                                'class' => [
                                                    'stuffs-item-image'
                                                ],
                                                'data' => [
                                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                                ],
                                                'style' => [
                                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                                ]
                                            ]) ?>
                                            <div class="stuffs-item-information">
                                                <div class="stuffs-item-name"><?= $arItem['NAME'] ?></div>
                                                <div class="stuffs-item-position"><?= $sPosition ?></div>
                                                <div class="stuffs-item-delimiter"></div>
                                                <div class="stuffs-item-phone">
                                                    <?php if (!empty($sPhone)) { ?>
                                                        <?= Loc::getMessage('N_L_STUFFS_PHONE').':' ?>
                                                        <?= Html::tag('a', $sPhone, [
                                                            'href' => 'tel:'. StringHelper::replace($sPhone, [
                                                                '(' => '',
                                                                ')' => '',
                                                                ' ' => '',
                                                                '-' => ''
                                                            ])
                                                        ]) ?>
                                                    <?php } ?>
                                                </div>
                                                <div class="stuffs-item-skype">
                                                    <?php if (!empty($sSkype)) { ?>
                                                        <?= Loc::getMessage('N_L_STUFFS_SKYPE').':' ?>
                                                        <?= Html::tag('a', $sSkype, [
                                                            'href' => 'skype:'.$sSkype
                                                        ]) ?>
                                                    <?php } ?>
                                                </div>
                                                <div class="stuffs-item-email">
                                                    <?php if (!empty($sEmail)) { ?>
                                                        <?= Loc::getMessage('N_L_STUFFS_EMAIL').':' ?>
                                                        <?= Html::tag('a', $sEmail, [
                                                            'href' => 'mailto:'.$sEmail
                                                        ]) ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php $bSectionFirst = false ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
