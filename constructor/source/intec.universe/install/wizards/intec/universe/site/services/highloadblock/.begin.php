<? include(__DIR__.'/../.begin.php') ?>
<?

use Bitrix\Main\Loader;
use Bitrix\Highloadblock;
use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

if (!Loader::includeModule('highloadblock'))
    return;

$import = function ($code, $table, $fields = [], $values = []) {
    $block = Highloadblock\HighloadBlockTable::getList([
        'filter' => ['NAME' => $code]
    ])->fetch();

    if ($block !== false)
        return;

    $block = Highloadblock\HighloadBlockTable::add([
        'NAME' => $code,
        'TABLE_NAME' => $table
    ])->getId();

    $block = Highloadblock\HighloadBlockTable::getById($block)->fetch();
    $entity = Highloadblock\HighloadBlockTable::compileEntity($block);
    $number = 0;

    foreach ($fields as $field) {
        if ($entity->hasField('UF_'.$field['FIELD_NAME']))
            continue;

        $number++;
        $name = ArrayHelper::getValue($field, 'NAME');

        unset($field['NAME']);

        $field['ENTITY_ID'] = 'HLBLOCK_'.$block['ID'];
        $field['FIELD_NAME'] = 'UF_'.$field['FIELD_NAME'];
        $field['SORT'] = (100 * $number);

        $field['EDIT_FORM_LABEL'] = $name;
        $field['LIST_COLUMN_LABEL'] = $name;
        $field['LIST_FILTER_LABEL'] = $name;

        (new CUserTypeEntity())->Add($field);
    }

    $entity = Highloadblock\HighloadBlockTable::compileEntity($block);
    $entityData = $entity->getDataClass();

    foreach ($values as $value) {
        $data = [];

        foreach ($value as $key => $part)
            $data['UF_'.$key] = $part;

        $entityData::add($data);
    }

    return;
};