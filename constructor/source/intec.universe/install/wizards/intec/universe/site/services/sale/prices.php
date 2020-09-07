<? include(__DIR__.'/../.begin.php') ?>
<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\base\Collection;
use intec\core\collections\Arrays;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

if ($mode === WIZARD_MODE_UPDATE)
    return;

Loc::loadMessages(__FILE__);

if (Loader::includeModule('sale') && Loader::includeModule('catalog')) {
    $arPrices = Arrays::fromDBResult(CCatalogGroup::GetList(['SORT' => 'ASC'], []));
    $arPrice = $arPrices->where(function ($sKey, $arPrice) {
        return $arPrice['BASE'] === 'Y';
    })->getFirst();

    if (empty($arPrice)) {
        if ($arPrices->isEmpty()) {
            (new CCatalogGroup())->Add([
                'BASE' => 'Y',
                'NAME' => 'BASE',
                'SORT' => 500,
                'USER_GROUP' => [1, 2],
                'USER_GROUP_BUY' => [1, 2],
                'USER_LANG' => [
                    'ru' => Loc::getMessage('wizard.services.sale.prices.price.name', null, 'ru'),
                    'en' => 'Base'
                ]
            ]);
        } else {
            $arPrice = $arPrices->getFirst();

            (new CCatalogGroup())->Update($arPrice['ID'], [
                'BASE' => 'Y'
            ]);
        }
    }
}

?>
<? include(__DIR__.'/../.end.php') ?>