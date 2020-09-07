<? include(__DIR__.'/../.begin.php') ?>
<?

use Bitrix\Main\Loader;
use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

if (!Loader::includeModule('form'))
    return;

$import = function ($code, $localization = [], $fields = [], $statuses = []) {
    $code = $code.'_'.WIZARD_SITE_ID;
    $form = CForm::GetBySID($code);
    $form = $form->GetNext();

    if (!empty($form)) {
        $form['FIELDS'] = [];
        $form['STATUSES'] = [];
        $fields = CFormField::GetList($form['ID'], 'N', $by = 's_sort', $order = 'asc', [], $filtered = false);
        $statuses = CFormStatus::GetList($form['ID'], $by = 's_sort', $order = 'asc', [], $filtered = false);

        while ($field = $fields->GetNext()) {
            $field['ANSWERS'] = [];
            $answers = CFormAnswer::GetList($field['ID'], $by = 's_sort', $order = 'asc', [], $filtered = false);

            while($answer = $answers->GetNext())
                $field['ANSWERS'][] = $answer;

            $form['FIELDS'][$field['SID']] = $field;
        }

        while ($status = $statuses->GetNext())
            $form['STATUSES'][] = $status;

        return $form;
    }

    if (!Type::isArray($localization))
        $localization = [];

    if (!Type::isArray($fields))
        $fields = [];

    if (!Type::isArray($statuses))
        $statuses = [];

    $language = LANGUAGE_ID;
    $site = WIZARD_SITE_ID;
    $localization = ArrayHelper::merge([
        'name' => '',
        'button' => '',
        'description' => ''
    ], $localization);

    $form = CForm::Set([
        'NAME' => $localization['name'],
        'SID' => $code,
        'C_SORT' => 100,
        'BUTTON' => $localization['button'],
        'DESCRIPTION' => $localization['description'],
        'DESCRIPTION_TYPE' => 'text',
        'STAT_EVENT1' => 'form',
        'STAT_EVENT2' => '',
        'arSITE' => [$site],
        'arMENU' => [
            $language => $localization['name']
        ],
        'arGROUP' => [
            '2' => '10'
        ]
    ]);

    if ($form === false)
        return null;

    $form = CForm::GetByID($form);
    $form = $form->GetNext();
    $form['FIELDS'] = [];
    $form['STATUSES'] = [];

    $fieldNumber = 0;

    foreach ($fields as $field) {
        $fieldNumber++;
        $name = ArrayHelper::getValue($field, 'name');
        $code = ArrayHelper::getValue($field, 'code');
        $type = ArrayHelper::getValue($field, 'type');
        $required = ArrayHelper::getValue($field, 'required');
        $values = ArrayHelper::getValue($field, 'values');

        $field = (new CFormField())->Set([
            'FORM_ID' => $form['ID'],
            'SID' => $code,
            'C_SORT' => (100 * $fieldNumber),
            'FIELD_TYPE' => 'text',
            'ACTIVE' => 'Y',
            'TITLE' => $name,
            'REQUIRED' => $required ? 'Y' : 'N'
        ]);

        if ($field === false)
            continue;

        $field = (new CFormField())->GetByID($field);
        $field = $field->GetNext();
        $field['ANSWERS'] = [];

        if (!Type::isArray($values))
            $values = [];

        if (empty($values) || !ArrayHelper::isIn($type, ['checkbox', 'radio']))
            $values[] = ' ';

        $number = 0;

        foreach ($values as $value) {
            $number++;
            $answer = (new CFormAnswer())->Set([
                'QUESTION_ID' => $field['ID'],
                'ACTIVE' => 'Y',
                'C_SORT' => (100 * $number),
                'MESSAGE' => $value,
                'FIELD_TYPE' => $type
            ]);

            if ($answer !== false) {
                $answer = (new CFormAnswer())->GetByID($answer);
                $answer = $answer->GetNext();

                $field['ANSWERS'][] = $answer;
            }
        }

        $form['FIELDS'][$field['SID']] = $field;
    }

    $number = 0;

    foreach ($statuses as $status) {
        $name = ArrayHelper::getValue($status, 'name');
        $description = ArrayHelper::getValue($status, 'description');
        $default = ArrayHelper::getValue($status, 'default');

        $status = (new CFormStatus())->Set([
            'FORM_ID' => $form['ID'],
            'ACTIVE' => 'Y',
            'TITLE' => $name,
            'DESCRIPTION' => $description,
            "arPERMISSION_VIEW"   => [2],
            "arPERMISSION_MOVE"   => [2],
            "arPERMISSION_EDIT"   => [],
            "arPERMISSION_DELETE" => [],
            'DEFAULT_VALUE' => $default ? 'Y' : 'N'
        ]);

        if ($status === false)
            continue;

        $status = (new CFormStatus())->GetByID($status);
        $status = $status->GetNext();

        $form['STATUSES'][] = $status;
    }
    
    return $form;
};