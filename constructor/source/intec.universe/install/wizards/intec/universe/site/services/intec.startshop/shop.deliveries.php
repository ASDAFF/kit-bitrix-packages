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
    'CODE' => 'PICKUP',
    'ACTIVE' => 'Y',
    'PRICE' => 0,
    'SID' => WIZARD_SITE_ID,
    'LANG' => []
], [
    'CODE' => 'COURIER',
    'ACTIVE' => 'Y',
    'PRICE' => 500,
    'SID' => WIZARD_SITE_ID,
    'LANG' => []
]];

$sort = 0;

foreach ($items as $item) {
    $sort += 100;
    $item['SORT'] = $sort;

    foreach ($languages as $id => $language) {
        $name = Loc::getMessage('wizard.services.sale.delivery.'.$item['CODE'].'.name', null, $id);

        if (!empty($name))
            $item['LANG'][$id] = [
                'NAME' => $name
            ];
    }

    CStartShopDelivery::Add($item);
}

?>
<? include(__DIR__.'/.end.php') ?>