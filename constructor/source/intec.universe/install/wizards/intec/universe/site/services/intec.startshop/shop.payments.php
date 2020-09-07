<? include(__DIR__.'/.begin.php') ?>
<?

use Bitrix\Main\Localization\Loc;
use intec\core\base\Collection;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

if ($mode === WIZARD_MODE_UPDATE)
    return;

$items = [[
    'CODE' => 'CASH',
    'ACTIVE' => 'Y',
    'HANDLER' => '',
    'CURRENCY' => 'RUB',
    'LANG' => []
]];

foreach ($items as $item) {
    $sort += 100;
    $item['SORT'] = $sort;

    foreach ($languages as $id => $language) {
        $name = Loc::getMessage('wizard.services.sale.payment.'.$item['CODE'].'.name', null, $id);

        if (!empty($name))
            $item['LANG'][$id] = [
                'NAME' => $name
            ];
    }

    CStartShopPayment::Add($item);
}

?>
<? include(__DIR__.'/.end.php') ?>