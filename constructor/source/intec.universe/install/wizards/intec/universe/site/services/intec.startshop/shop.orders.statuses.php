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
    'CODE' => 'NEW',
    'SID' => WIZARD_SITE_ID,
    'CAN_PAY' => 'N',
    'DEFAULT' => 'Y',
    'LANG' => []
], [
    'CODE' => 'ACCEPTED',
    'SID' => WIZARD_SITE_ID,
    'CAN_PAY' => 'N',
    'DEFAULT' => 'N',
    'LANG' => []
], [
    'CODE' => 'COMPILED',
    'SID' => WIZARD_SITE_ID,
    'CAN_PAY' => 'Y',
    'DEFAULT' => 'N',
    'LANG' => []
], [
    'CODE' => 'PAID',
    'SID' => WIZARD_SITE_ID,
    'CAN_PAY' => 'N',
    'DEFAULT' => 'N',
    'LANG' => []
], [
    'CODE' => 'SHIPPED',
    'SID' => WIZARD_SITE_ID,
    'CAN_PAY' => 'N',
    'DEFAULT' => 'N',
    'LANG' => []
]];

$sort = 0;

foreach ($items as $item) {
    $sort += 100;
    $item['SORT'] = $sort;

    foreach ($languages as $id => $language) {
        $name = Loc::getMessage('wizard.services.sale.order.status.'.$item['CODE'].'.name', null, $id);

        if (!empty($name))
            $item['LANG'][$id] = [
                'NAME' => $name
            ];
    }

    CStartShopOrderStatus::Add($item);
}

?>
<? include(__DIR__.'/.end.php') ?>