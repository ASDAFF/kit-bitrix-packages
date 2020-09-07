<? include(__DIR__.'/.begin.php') ?>
<?

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use intec\core\base\Collection;

/**
 * @var Collection $data
 * @var array $languages
 * @var string $solution
 * @var CWizardBase $wizard
 * @var Closure($code, $type, $file, $fields = []) $import
 * @var CWizardStep $this
 */

$code = $solution.'_products_'.WIZARD_SITE_ID;
$type = 'catalogs';
$file = 'catalogs.products';

if (ModuleManager::isModuleInstalled('sale')) {
    $file .= '.base';
} else {
    $file .= '.lite';
}

$iBlock = $import($code, $type, $file, [
    'CODE' => [
        'IS_REQUIRED' => 'N'
    ]
]);

if (!empty($iBlock)) {
    $macros = $data->get('macros');
    $macros['CATALOGS_PRODUCTS_IBLOCK_TYPE'] = $type;
    $macros['CATALOGS_PRODUCTS_IBLOCK_ID'] = $iBlock['ID'];
    $macros['CATALOGS_PRODUCTS_IBLOCK_CODE'] = $iBlock['CODE'];

    if (Loader::includeModule('intec.startshop') && $mode !== WIZARD_MODE_UPDATE) {
        $price = CStartShopPrice::GetByCode('BASE')->Fetch();

        if (!empty($price)) {
            $property = CIBlockProperty::GetList([], [
                'IBLOCK_ID' => $iBlock['ID'],
                'CODE' => 'STARTSHOP_PRICE'
            ])->Fetch();

            if (!empty($property))
                (new CIBlockProperty())->Update($property['ID'], [
                    'CODE' => 'STARTSHOP_PRICE_'.$price['ID']
                ]);

            $property = CIBlockProperty::GetList([], [
                'IBLOCK_ID' => $iBlock['ID'],
                'CODE' => 'STARTSHOP_CURRENCY'
            ])->Fetch();

            if (!empty($property))
                (new CIBlockProperty())->Update($property['ID'], [
                    'CODE' => 'STARTSHOP_CURRENCY_'.$price['ID']
                ]);
        }

        CStartShopCatalog::Add([
            'IBLOCK' => $iBlock['ID'],
            'USE_QUANTITY' => true
        ]);
    }

    $data->set('macros', $macros);
}

?>
<? include(__DIR__.'/.end.php') ?>