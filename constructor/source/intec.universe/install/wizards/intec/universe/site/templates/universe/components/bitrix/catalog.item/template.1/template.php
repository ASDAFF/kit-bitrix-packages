<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (!Loader::includeModule('intec.core'))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<?php if (isset($arResult['ITEM'])) {

    $item = &$arResult['ITEM'];
    $bOffers = !empty($item['OFFERS']);

    if (!empty($item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']))
        $sName = $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'];
    else
        $sName = $item['NAME'];

?>
    <?= Html::beginTag('div', [
        'id' => $sTemplateId,
        'class' => Html::cssClassFromArray([
            'ns-bitrix' => true,
            'c-catalog-item' => true,
            'c-catalog-item-template-1' => true,
        ], true)
    ]) ?>
        <div class="catalog-item-body">
            <?php include(__DIR__.'/parts/image.php') ?>
            <div class="catalog-item-name-container">
                <?= Html::tag('a', $sName, [
                    'class' => [
                        'catalog-item-name',
                        'intec-cl-text-hover'
                    ],
                    'href' => $item['DETAIL_PAGE_URL']
                ]) ?>
            </div>
            <?php include(__DIR__.'/parts/price.php') ?>
            <?php include(__DIR__.'/parts/buttons.php') ?>
        </div>
        <?php include(__DIR__.'/parts/script.php') ?>
	<?= Html::endTag('div') ?>
<?php } ?>
