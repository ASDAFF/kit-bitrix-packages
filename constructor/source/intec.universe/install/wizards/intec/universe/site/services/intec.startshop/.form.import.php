<?

use Bitrix\Main\Localization\Loc;

/**
 * @var array $languages
 * @var string $code
 * @var array $fields
 * @var Closure($code, $localization, $fields, $statuses) $import
 */

$localization = [];

foreach ($languages as $id => $language) {
    $localization[$id] = [
        'name' => Loc::getMessage('wizard.services.form.name', null, $id),
        'button' => Loc::getMessage('wizard.services.form.button', null, $id),
        'sent' => Loc::getMessage('wizard.services.form.sent', null, $id)
    ];

    foreach ($fields as $key => $field)
        $fields[$key]['localization'][$id] = [
            'name' => Loc::getMessage('wizard.services.form.fields.'.$field['code'].'.name', null, $id)
        ];
}

$form = $import($code, $localization, $fields);

?>