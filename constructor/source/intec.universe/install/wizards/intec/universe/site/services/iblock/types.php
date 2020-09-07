<? include('.begin.php') ?>
<?

use Bitrix\Main\Localization\Loc;
use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;

/**
 * @var Collection $data
 * @var array $languages
 * @var string $solution
 * @var CWizardBase $wizard
 * @var Closure($code, $type, $file, $fields = []) $import
 * @var CWizardStep $this
 */

Loc::loadMessages(__FILE__);

$types = [];

/** CUSTOM START */

$types[] = [
    'ID' => 'content',
    'SECTIONS' => 'Y',
    'IN_RSS' => 'N'
];

$types[] = [
    'ID' => 'catalogs',
    'SECTIONS' => 'Y',
    'IN_RSS' => 'N'
];

/** CUSTOM END */

$sort = 0;

foreach ($types as $type) {
    $sort += 100;

    if (CIBlockType::GetByID($type['ID'])->GetNext())
        continue;

    $type = ArrayHelper::merge([
        'ID' => null,
        'SECTIONS' => 'Y',
        'IN_RSS' => 'N',
        'SORT' => $sort,
        'LANG' => []
    ], $type);

    if (empty($type['ID']))
        continue;

    foreach ($languages as $id => $language) {
        $code = 'wizard.services.iblock.type.'.$type['ID'];
        $language = [
            'NAME' => Loc::getMessage($code.'.name', null, $id),
            'ELEMENT_NAME' => Loc::getMessage($code.'.element', null, $id)
        ];

        if ($type['SECTIONS'] == 'Y')
            $language['SECTION_NAME'] = Loc::getMessage($code.'.section', null, $id);

        if (empty($language['NAME']))
            continue;

        $type['LANG'][$id] = $language;
    }

    (new CIBlockType())->Add($type);
}

/** CUSTOM START */
/** CUSTOM END */

?>
<? include('.end.php') ?>