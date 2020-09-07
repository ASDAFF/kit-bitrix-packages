<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$bBase = false;
$bLite = false;

if (Loader::includeModule('catalog') && Loader::includeModule('sale'))
    $bBase = true;
else if (Loader::includeModule('intec.startshop'))
    $bLite = true;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];
$arData = $arResult['DATA'];
$arCatalogData = [];

include(__DIR__.'/parts/data.php');

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-element',
        'c-catalog-element-catalog-default-4'
    ],
    'data' => [
        'data' => Json::encode($arCatalogData, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true),
        'properties' => Json::encode($arResult['SKU_PROPS'], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true),
        'available' => $arCatalogData['available'] ? 'true' : 'false',
        'subscribe' => $arCatalogData['subscribe'] ? 'true' : 'false'
    ]
]) ?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <div class="catalog-element-content">
                <div class="catalog-element-header">
                    <div class="intec-grid intec-grid-a-v-center intec-grid-i-h-16 intec-grid-i-v-8 intec-grid-768-wrap">
                        <?php if ($arVisual['ARTICLE']['SHOW']) {
                            include(__DIR__.'/parts/article.php');
                        } ?>
                        <?php if ($arVisual['QUANTITY']['SHOW']) {
                            include(__DIR__.'/parts/quantity.php');
                        } ?>
                        <?php if ($arVisual['VOTE']['SHOW']) {
                            include(__DIR__.'/parts/vote.php');
                        } ?>
                    </div>
                </div>
                <div class="catalog-element-columns catalog-element-section">
                    <div class="intec-grid intec-grid-i-16 intec-grid-768-wrap">
                        <div class="catalog-element-columns-left intec-grid-item-auto intec-grid-item-1024-2 intec-grid-item-768-1">
                            <?php if ($arVisual['GALLERY']['SHOW']) {
                                include(__DIR__.'/parts/gallery.php');
                            } ?>
                            <?php if ($arVisual['DESCRIPTION']['PREVIEW']['SHOW']) {
                                include(__DIR__.'/parts/description.preview.php');
                            } ?>
                            <?php if ($arVisual['PROPERTIES']['PREVIEW']['SHOW'] && $arVisual['PROPERTIES']['PREVIEW']['POSITION'] === 'left') {
                                include(__DIR__.'/parts/properties.preview.php');
                            } ?>
                        </div>
                        <div class="catalog-element-columns-right intec-grid-item intec-grid-item-1024-2 intec-grid-item-768-1">
                            <div class="catalog-element-block">
                                <div class="intec-grid intec-grid-a-v-center intec-grid-i-8 intec-grid-1024-wrap">
                                    <?php include(__DIR__.'/parts/price.php') ?>
                                    <?php if ($arVisual['BRAND']['SHOW']) {
                                        include(__DIR__.'/parts/brand.php');
                                    } ?>
                                </div>
                            </div>
                            <?php include(__DIR__.'/parts/price.range.php') ?>
                            <?php if ($arVisual['ADDITIONAL']['SHOW']) {
                                include(__DIR__.'/parts/additional.php');
                            } ?>
                            <?php if (!empty($arResult['OFFERS'])) {
                                include(__DIR__.'/parts/offers.php');
                            } ?>
                            <?php if ($arResult['ACTION'] !== 'none') {
                                include(__DIR__.'/parts/purchase.php');
                            } ?>
                            <?php if ($arVisual['INFORMATION']['PAYMENT']['SHOW'] || $arVisual['INFORMATION']['SHIPMENT']['SHOW']) {
                                include(__DIR__.'/parts/information.php');
                            } ?>
                            <?php if ($arVisual['PROPERTIES']['PREVIEW']['SHOW'] && $arVisual['PROPERTIES']['PREVIEW']['POSITION'] === 'right') {
                                include(__DIR__.'/parts/properties.preview.php');
                            } ?>
                        </div>
                    </div>
                </div>
                <?php if ($arVisual['DESCRIPTION']['DETAIL']['SHOW']) {
                    include(__DIR__.'/parts/description.detail.php');
                } ?>
                <?php if ($arVisual['DOCUMENTS']['SHOW']) {
                    include(__DIR__.'/parts/documents.php');
                } ?>
                <?php if ($arVisual['PROPERTIES']['DETAIL']['SHOW']) {
                    include(__DIR__.'/parts/properties.detail.php');
                } ?>
            </div>
        </div>
    </div>
    <?php include(__DIR__.'/parts/script.php') ?>
<?= Html::endTag('div') ?>