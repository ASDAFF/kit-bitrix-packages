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

$getMapCoordinates = function ($arItem) use ($arParams) {
    $arPropertyMap = ArrayHelper::getValue($arItem, ['PROPERTIES',$arParams['PROPERTY_MAP']]);
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
<div class="widget c-widget c-widget-contacts-1" id="<?= $sTemplateId ?>">
    <div class="widget-maps-wrap">
        <div class="widget-items-wrap">
            <div class="widget-items-wrap-2 intec-content intec-content-visible">
                <div class="widget-items-wrap-3 intec-content-wrapper">
                    <div class="widget-items intec-grid intec-grid-a-v-stretch intec-grid-1024-wrap" data-role="contacts">
                        <?php include(__DIR__.'/parts/items.php') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="widget-maps" data-role="maps">
            <?php include(__DIR__.'/parts/maps.php') ?>
        </div>
    </div>
</div>
<?php include(__DIR__.'/parts/script.php') ?>
