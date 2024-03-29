<? include(__DIR__.'/.begin.php') ?>
<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\base\Collection;

/**
 * @var Collection $data
 * @var array $languages
 * @var CWizardBase $wizard
 * @var Closure($code, $localization, $fields, $statuses) $import, где
 * $fields массив полей, которые имеют следующие поля:
 * - string name - Имя поля (Обязательное).
 * - string code - Код поля (Обязательное).
 * - string send - Сообщение после отправки.
 * - string type - Тип поля (Обязательное).
 * - string required - Является обязательным полем.
 * - string readonly - Поле только для чтения.
 * - array values - Возможные значения поля (если тип checkbox или radio).
 * - array localization - Языковые параметры.
 * @var CWizardStep $this
 */

Loc::loadMessages(__FILE__);

$code = 'VACANCY';
$fields = [[
    'code' => 'INITIALS',
    'type' => 0,
    'required' => 'Y',
    'readonly' => 'N'
], [
    'code' => 'PHONE',
    'type' => 0,
    'required' => 'Y',
    'readonly' => 'N'
], [
    'code' => 'EMAIL',
    'type' => 0,
    'required' => 'N',
    'readonly' => 'N'
], [
    'code' => 'POST',
    'type' => 0,
    'required' => 'Y',
    'readonly' => 'N'
]];

include(__DIR__.'/.form.import.php');
/** @var array $form */

if (!Loader::includeModule('form'))
    if (!empty($form)) {
        $macros = $data->get('macros');
        $macros['FORMS_'.$code.'_ID'] = $form['ID'];
        $macros['FORMS_'.$code.'_FIELDS_POST_ID'] = '';

        $field = CStartShopFormProperty::GetList([], [
            'CODE' => 'POST',
            'FORM' => $form['ID']
        ])->Fetch();

        if (!empty($field))
            $macros['FORMS_'.$code.'_FIELDS_POST_ID'] = $field['ID'];

        $data->set('macros', $macros);
    }

?>
<? include(__DIR__.'/.end.php') ?>