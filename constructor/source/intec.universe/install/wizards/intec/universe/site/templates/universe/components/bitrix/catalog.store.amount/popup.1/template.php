<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (!Loader::includeModule('intec.core'))
    return;

$arVisual = $arResult['VISUAL'];

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<?php if (!empty($arResult['STORES'])) { ?>
    <div class="ns-bitrix c-catalog-store-amount c-catalog-store-amount-popup-1" id="<?= $sTemplateId ?>">
        <div class="catalog-store-amount-items">
            <?php foreach ($arResult['STORES'] as $arStore) { ?>
                <div class="catalog-store-amount-item" data-store-id="<?= $arStore['ID'] ?>">
                    <?= Html::tag('div', $arStore['TITLE'], [
                        'class' => 'catalog-store-amount-name'
                    ]) ?>
                    <div class="intec-grid intec-grid-a-v-center intec-grid-i-4">
                        <div class="intec-grid-item-auto">
                            <?= Html::tag('div', null, [
                                'class' => 'catalog-store-amount-indicator',
                                'data' => [
                                    'role' => 'store.state',
                                    'store-state' => $arStore['AMOUNT_STATUS']
                                ]
                            ]) ?>
                        </div>
                        <div class="intec-grid-item-auto">
                            <div class="catalog-store-amount-quantity" data-store-state="<?= $arStore['AMOUNT_STATUS'] ?>">
                                <?= Html::tag('span', $arStore['AMOUNT_PRINT'], [
                                    'data-role' => 'store.quantity'
                                ]) ?>
                                <?php if (!$arVisual['MIN_AMOUNT']['USE']) { ?>
                                    <?= Html::tag('span', !$arResult['IS_SKU'] ? ArrayHelper::getFirstValue($arResult['MEASURES']) : null, [
                                        'data-role' => 'store.measure'
                                    ]) ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php unset($arStore) ?>
    </div>
    <?php if ($arResult['IS_SKU']) { ?>
        <script type="text/javascript">
            $(document).on('ready', function () {
                var storeOffers = new intecCatalogStoreOffers(
                    <?= JavaScript::toObject('#'.$sTemplateId) ?>,
                    <?= JavaScript::toObject($arResult['JS']) ?>
                );

                offers.on('change', function (event, offer, values) {
                    storeOffers.offerOnChange(offer.id);
                });

                storeOffers.offerOnChange(offers.getCurrent().id);
            })
        </script>
    <?php } ?>
<?php } ?>
