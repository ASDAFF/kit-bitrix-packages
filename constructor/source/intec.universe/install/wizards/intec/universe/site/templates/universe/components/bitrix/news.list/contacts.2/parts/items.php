<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arIcons
 */

$arVisual = $arResult['VISUAL'];
?>
<?php foreach ($arResult['ITEMS'] as $arItem) {

    $sId = $sTemplateId.'_'.$arItem['ID'];
    $sAreaId = $this->GetEditAreaId($sId);
    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

    $arData = $arItem['DATA'];

    $sPicture = $arItem['PREVIEW_PICTURE'];

    if (empty($sPicture))
        $sPicture = $arItem['DETAIL_PICTURE'];

    if (!empty($sPicture)) {
        $sPicture = CFile::ResizeImageGet($sPicture, [
            'width' => 310,
            'height' => 310
        ], BX_RESIZE_IMAGE_PROPORTIONAL);

        if (!empty($sPicture))
            $sPicture = $sPicture['src'];
    }

    if (empty($sPicture))
        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

    $sTag = 'div';
    if (!empty($arData['STORE']['LINK']))
        $sTag = 'a';

?>
    <div class="news-list-item" id="<?= $sAreaId ?>" data-role="items.item" itemscope="" itemtype="http://schema.org/Organization">
        <span itemprop="name" style="display: none"><?= $arParams['MARKUP_COMPANY']?></span>
		<div class="news-list-item-wrapper intec-grid intec-grid-650-wrap">
            <div class="intec-grid-item-auto intec-grid-item-650-1">
                <?= Html::tag($sTag, '', [
                    'class' => 'news-list-item-picture',
                    'data' => [
                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                    ],
                    'style' => [
                        'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                    ],
                    'href' => $sTag == 'a'? $arData['STORE']['LINK'] : null
                ]) ?>
            </div>
            <div class="news-list-item-content intec-grid-item intec-grid-item-650-1">
                <?php if (!empty($arData['LIST']['NAME'])) { ?>
                    <?= Html::tag($sTag, $arData['LIST']['NAME'].':', [
                        'class' => 'news-list-item-name',
                        'href' => $sTag == 'a'? $arData['STORE']['LINK'] : null
                    ]) ?>
                <?php } ?>
                <div class="news-list-item-contacts intec-grid intec-grid-1024-wrap">
                    <?php if ($arVisual['ADDRESS']['SHOW'] && !empty($arData['ADDRESS'])) { ?>
                        <div class="news-list-item-contact intec-grid-item-3 intec-grid-item-1024-2 intec-grid-item-768-1 intec-grid-item-650-2 intec-grid-item-450-1" itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
                            <div class="news-list-item-contact-wrapper">
                                <div class="news-list-item-contact-name intec-grid intec-grid-a-v-center">
                                    <div class="news-list-item-contact-name-icon intec-grid-item-auto">
                                        <?= $arIcons['location'] ?>
                                    </div>
                                    <div class="news-list-item-contact-name-value intec-grid-item">
                                        <?= Loc::getMessage('C_NEWS_LIST_CONTACTS_2_BLOCK_ADDRESS') ?>
                                    </div>
                                </div>
                                <div class="news-list-item-contact-value">
                                    <?php if (!empty($arData['INDEX'])) { ?>
                                        <span itemprop="postalCode"><?= $arData['INDEX'] ?></span>,
                                    <?php } ?>
                                    <?php if (!empty($arData['CITY'])) { ?>
                                        <span itemprop="addressLocality"><?= $arData['CITY'] ?></span><br>
                                    <?php } ?>
                                    <?php if (!empty($arData['ADDRESS'])) { ?>
                                        <span itemprop="streetAddress"><?= $arData['ADDRESS'] ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($arVisual['SCHEDULE']['SHOW'] && !empty($arData['SCHEDULE'])) { ?>
                        <div class="news-list-item-contact intec-grid-item-3 intec-grid-item-1024-2 intec-grid-item-768-1 intec-grid-item-650-2 intec-grid-item-450-1">
                            <div class="news-list-item-contact-wrapper">
                                <div class="news-list-item-contact-name intec-grid intec-grid-a-v-center">
                                    <div class="news-list-item-contact-name-icon intec-grid-item-auto">
                                        <?= $arIcons['time'] ?>
                                    </div>
                                    <div class="news-list-item-contact-name-value intec-grid-item">
                                        <?= Loc::getMessage('C_NEWS_LIST_CONTACTS_2_BLOCK_TIME') ?>
                                    </div>
                                </div>
                                <div class="news-list-item-contact-value">
                                    <?php if (Type::isArray($arData['SCHEDULE'])) { ?>
                                        <?php foreach ($arData['SCHEDULE'] as $sTime) { ?>
                                            <div>
                                                <?= $sTime ?>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?= $arData['SCHEDULE'] ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($arVisual['PHONE']['SHOW'] && !empty($arData['PHONE'])) { ?>
                        <div class="news-list-item-contact intec-grid-item-3 intec-grid-item-1024-2 intec-grid-item-768-1 intec-grid-item-650-2 intec-grid-item-450-1">
                            <div class="news-list-item-contact-wrapper">
                                <div class="news-list-item-contact-name intec-grid intec-grid-a-v-center">
                                    <div class="news-list-item-contact-name-icon intec-grid-item-auto">
                                        <?= $arIcons['contacts'] ?>
                                    </div>
                                    <div class="news-list-item-contact-name-value intec-grid-item">
                                        <?= Loc::getMessage('C_NEWS_LIST_CONTACTS_2_BLOCK_PHONE') ?>
                                    </div>
                                </div>
                                <div class="news-list-item-contact-value">
                                    <?php if (Type::isArray($arData['PHONE'])) { ?>
                                        <?php foreach ($arData['PHONE'] as $sPhone) { ?>
                                            <div class="news-list-item-contact-value-item" itemprop="telephone">
                                                <?= $sPhone ?>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="news-list-item-contact-value-item" itemprop="telephone">
                                            <?= $arData['PHONE'] ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arVisual['EMAIL']['SHOW'] && !empty($arData['EMAIL'])) { ?>
                                        <a href="mailto:<?= $arData['EMAIL'] ?>" itemprop="email"><?= $arData['EMAIL'] ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php if (!empty($arData['MAP']['LOCATION'])) {
            $arCoordinates = $getMapCoordinates($arItem);
        ?>
            <?= Html::beginTag('div', [
                'class' => 'news-list-item-map intec-grid intec-grid-a-v-center intec-cl-background-hover',
                'data' => [
                    'latitude' => $arCoordinates[0],
                    'longitude' => $arCoordinates[1],
                    'role' => 'button',
                    'list' => 'additional'
                ],
            ]) ?>
                <div class="news-list-item-map-icon intec-grid-item-auto">
                    <?= $arIcons['map'] ?>
                </div>
                <div class="news-list-item-map-text intec-grid-item">
                    <?= Loc::getMessage('C_NEWS_LIST_CONTACTS_2_MAP_BUTTON_SHOW') ?>
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    </div>
<?php } ?>
