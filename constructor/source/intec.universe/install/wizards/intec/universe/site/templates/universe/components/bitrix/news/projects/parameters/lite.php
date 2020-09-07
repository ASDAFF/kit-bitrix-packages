<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arCurrentValues
 * @var array $arForms
 */

$dbForms = CStartShopForm::GetList();
while ($row = $dbForms->Fetch()) {
    $name = ArrayHelper::getValue($row, ['LANG', LANGUAGE_ID, 'NAME'], $row['CODE']);
    $arForms[$row['ID']] = '[' . $row['ID'] . '] ' . $name;
}
unset($dbForms);

if ($arCurrentValues['DISPLAY_FORM_ORDER'] == 'Y' && !empty($arCurrentValues['FORM_ORDER'])) {

    $rsFormFields = CStartShopFormProperty::GetList(array(), array('FORM' => $arCurrentValues['FORM_ORDER']));

    while ($row = $rsFormFields->GetNext()) {
        $name = ArrayHelper::getValue($row, ['LANG', 'ru', 'NAME'], '-');
        $arFormFields[$row['ID']] = '[' . $row['ID'] . '] ' . $name;
    }
}