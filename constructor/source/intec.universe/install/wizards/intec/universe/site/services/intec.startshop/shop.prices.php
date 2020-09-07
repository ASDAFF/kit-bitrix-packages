<? include(__DIR__.'/.begin.php') ?>
<?

use Bitrix\Main\Localization\Loc;
use intec\core\base\Collection;

/**
 * @var Collection $data
 * @var array $languages
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

if ($mode === WIZARD_MODE_UPDATE)
    return;

$items = [[
    'CODE' => 'BASE',
    'ACTIVE' => 'Y',
    'BASE' => 'Y',
    'GROUPS' => ['2'],
    'LANG' => []
]];

$sort = 0;

foreach ($items as $item) {
    $sort += 100;
    $item['SORT'] = $sort;

    foreach ($languages as $id => $language) {
        $name = Loc::getMessage('wizard.services.sale.price.'.$item['CODE'].'.name', null, $id);

        if (!empty($name))
            $item['LANG'][$id] = [
                'NAME' => $name
            ];
    }

    CStartShopPrice::Add($item);
}

?>
<? include(__DIR__.'/.end.php') ?>