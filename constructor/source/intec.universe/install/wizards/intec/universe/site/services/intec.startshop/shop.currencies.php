<? include(__DIR__.'/.begin.php') ?>
<?

use Bitrix\Main\Localization\Loc;
use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var Collection $data
 * @var string $mode
 * @var array $languages
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

if ($mode === WIZARD_MODE_UPDATE)
    return;

$items = [[
    'CODE' => 'RUB',
    'ACTIVE' => 'Y',
    'BASE' => 'Y',
    'RATE' => '1',
    'RATING' => '1',
    'LANG' => [],
    'FORMAT' => [
        'ru' => [
            'DELIMITER_DECIMAL' => '.',
            'DELIMITER_THOUSANDS' => ' ',
            'DECIMALS_COUNT' => '2',
            'DECIMALS_DISPLAY_ZERO' => 'N'
        ]
    ]
], [
    'CODE' => 'USD',
    'ACTIVE' => 'Y',
    'BASE' => 'N',
    'RATE' => '60',
    'RATING' => '1',
    'LANG' => [],
    'FORMAT' => [
        'ru' => [
            'DELIMITER_DECIMAL' => '.',
            'DELIMITER_THOUSANDS' => ' ',
            'DECIMALS_COUNT' => '2',
            'DECIMALS_DISPLAY_ZERO' => 'N'
        ]
    ]
]];

$sort = 0;

foreach ($items as $item) {
    $sort += 100;
    $item['SORT'] = $sort;

    foreach ($languages as $id => $language) {
        $name = Loc::getMessage('wizard.services.sale.currency.'.$item['CODE'].'.name', null, $id);

        if (!empty($name))
            $item['LANG'][$id] = [
                'NAME' => $name
            ];

        $format = ArrayHelper::getValue($item, ['FORMAT', $id]);
        $format = Type::isArray($format) ? $format : [];
        $format['FORMAT'] = Loc::getMessage('wizard.services.sale.currency.'.$item['CODE'].'.format', null, $id);

        if (!empty($format['FORMAT']))
            $item['FORMAT'][$id] = $format;
    }

    CStartShopCurrency::Add($item);
}

?>
<? include(__DIR__.'/.end.php') ?>