<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arIcons = [
    'location' => FileHelper::getFileData(__DIR__.'/images/location.svg'),
    'time' => FileHelper::getFileData(__DIR__.'/images/time.svg'),
    'contacts' => FileHelper::getFileData(__DIR__.'/images/contacts.svg'),
    'map' => FileHelper::getFileData(__DIR__.'/images/map.svg')
];

$getMapCoordinates = function ($arItem) use ($arParams) {
    $arPropertyMap = ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_MAP_LOCATION']]);

    if (empty($arPropertyMap['VALUE']))
        return null;

    $arCoordinates = StringHelper::explode($arPropertyMap['VALUE']);
    if (!empty($arCoordinates) && count($arCoordinates) == 2) {
        $arCoordinates[0] = Type::toFloat($arCoordinates[0]);
        $arCoordinates[1] = Type::toFloat($arCoordinates[1]);

        return $arCoordinates;
    }

    return null;
};
?>
<div class="ns-bitrix c-news-list c-news-list-contacts-2" id="<?= $sTemplateId ?>">
    <div class="news-list-maps-wrap">
        <div class="news-list-short-items-wrap">
            <div class="intec-content intec-content-visible">
                <div class="intec-content-wrapper">
                    <div class="news-list-short-items intec-grid intec-grid-a-v-stretch intec-grid-1024-wrap">
                        <?php include(__DIR__.'/parts/items.short.php') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="news-list-maps" data-role="maps">
            <?php include(__DIR__.'/parts/maps.php') ?>
        </div>
    </div>
    <div class="news-list-items-wrap">
        <div class="intec-content intec-content-visible">
            <div class="intec-content-wrapper">
                <div class="news-list-items">
                    <?php include(__DIR__.'/parts/items.php') ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include(__DIR__.'/parts/script.php') ?>
