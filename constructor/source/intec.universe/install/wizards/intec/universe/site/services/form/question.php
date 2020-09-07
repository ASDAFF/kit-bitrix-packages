<? include('.begin.php') ?>
<?

use Bitrix\Main\Localization\Loc;
use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var Closure($code, $localization, $fields, $statuses) $import, где
 * $fields массив полей, которые имеют следующие поля:
 * - string name - Имя поля (Обязательное).
 * - string code - Код поля (Обязательное).
 * - string type - Тип поля (Обязательное).
 * - bool required - Является обязательным полем.
 * - array values - Возможные значения поля (если тип checkbox или radio).
 * $statuses массив статусов, которые имеют следующие поля:
 * - string name - Имя статуса (Обязательное).
 * - string description - Описание статуса.
 * - bool default - Является статусом по умолчанию.
 * @var CWizardStep $this
 */

Loc::loadMessages(__FILE__);

$code = 'QUESTION';
$form = $import($code, [
    'name' => Loc::getMessage('wizard.services.form.name'),
    'button' => Loc::getMessage('wizard.services.form.button'),
    'description' => Loc::getMessage('wizard.services.form.description')
], [[
    'name' => Loc::getMessage('wizard.services.form.fields.NAME.name'),
    'code' => 'NAME',
    'type' => 'text',
    'required' => true
], [
    'name' => Loc::getMessage('wizard.services.form.fields.EMAIL.name'),
    'code' => 'EMAIL',
    'type' => 'email',
    'required' => true
], [
    'name' => Loc::getMessage('wizard.services.form.fields.PHONE.name'),
    'code' => 'PHONE',
    'type' => 'text',
    'required' => false
], [
    'name' => Loc::getMessage('wizard.services.form.fields.TEXT.name'),
    'code' => 'TEXT',
    'type' => 'textarea',
    'required' => true
]], [[
    'name' => Loc::getMessage('wizard.services.form.status'),
    'description' => '',
    'default' => true
]]);

if (!empty($form)) {
    $macros = $data->get('macros');
    $macros['FORMS_'.$code.'_ID'] = $form['ID'];

    $data->set('macros', $macros);
}

?>
<? include('.end.php') ?>