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
    'CODE' => 'LAST_NAME',
    'ACTIVE' => 'Y',
    'SID' => WIZARD_SITE_ID,
    'TYPE' => 'S',
    'REQUIRED' => 'Y',
    'SUBTYPE' => '',
    'DATA' => null,
    'USER_FIELD' => 'LAST_NAME',
    'LANG' => []
], [
    'CODE' => 'NAME',
    'ACTIVE' => 'Y',
    'SID' => WIZARD_SITE_ID,
    'TYPE' => 'S',
    'REQUIRED' => 'Y',
    'SUBTYPE' => '',
    'DATA' => null,
    'USER_FIELD' => 'NAME',
    'LANG' => []
], [
    'CODE' => 'PHONE',
    'ACTIVE' => 'Y',
    'SID' => WIZARD_SITE_ID,
    'TYPE' => 'S',
    'REQUIRED' => 'Y',
    'SUBTYPE' => '',
    'DATA' => null,
    'USER_FIELD' => 'PERSONAL_PHONE',
    'LANG' => []
], [
    'CODE' => 'EMAIL',
    'ACTIVE' => 'Y',
    'SID' => WIZARD_SITE_ID,
    'TYPE' => 'S',
    'REQUIRED' => 'Y',
    'SUBTYPE' => '',
    'DATA' => null,
    'USER_FIELD' => 'EMAIL',
    'LANG' => []
], [
    'CODE' => 'COMMENT',
    'ACTIVE' => 'Y',
    'SID' => WIZARD_SITE_ID,
    'TYPE' => 'S',
    'SUBTYPE' => '',
    'DATA' => null,
    'USER_FIELD' => '',
    'LANG' => []
], [
    'CODE' => 'ZIP',
    'ACTIVE' => 'Y',
    'SID' => WIZARD_SITE_ID,
    'TYPE' => 'S',
    'REQUIRED' => 'Y',
    'SUBTYPE' => '',
    'DATA' => null,
    'USER_FIELD' => 'PERSONAL_ZIP',
    'LANG' => []
], [
    'CODE' => 'STATE',
    'ACTIVE' => 'Y',
    'SID' => WIZARD_SITE_ID,
    'TYPE' => 'S',
    'REQUIRED' => 'N',
    'SUBTYPE' => '',
    'DATA' => null,
    'USER_FIELD' => 'PERSONAL_STATE',
    'LANG' => []
], [
    'CODE' => 'CITY',
    'ACTIVE' => 'Y',
    'SID' => WIZARD_SITE_ID,
    'TYPE' => 'S',
    'REQUIRED' => 'N',
    'SUBTYPE' => '',
    'DATA' => null,
    'USER_FIELD' => 'PERSONAL_CITY',
    'LANG' => []
], [
    'CODE' => 'STREET',
    'ACTIVE' => 'Y',
    'SID' => WIZARD_SITE_ID,
    'TYPE' => 'S',
    'REQUIRED' => 'N',
    'SUBTYPE' => '',
    'DATA' => null,
    'USER_FIELD' => 'PERSONAL_STREET',
    'LANG' => []
]];

$sort = 0;

foreach ($items as $item) {
    $sort += 100;
    $item['SORT'] = $sort;

    foreach ($languages as $id => $language) {
        $name = Loc::getMessage('wizard.services.sale.order.property.'.$item['CODE'].'.name', null, $id);

        if (!empty($name))
            $item['LANG'][$id] = [
                'NAME' => $name
            ];
    }

    CStartShopOrderProperty::Add($item);
}

?>
<? include(__DIR__.'/.end.php') ?>