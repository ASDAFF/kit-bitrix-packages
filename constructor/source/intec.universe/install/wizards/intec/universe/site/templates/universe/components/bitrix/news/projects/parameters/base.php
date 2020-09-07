<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

/**
 * @var array $arCurrentValues
 * @var array $arForms
 */

$rsForms = CForm::GetList(
    $by = 'SORT',
    $order = 'ASC',
    array('ACTIVE' => 'Y'),
    $filtered = false
);

while ($row = $rsForms->GetNext()) {
    $arForms[$row['ID']] = '[' . $row['ID'] . '] ' . $row['NAME'];
}
unset($rsForms);


if ($arCurrentValues['DISPLAY_FORM_ORDER'] == 'Y' && !empty($arCurrentValues['FORM_ORDER'])) {

    $rsFormFields = CFormField::GetList(
        $arCurrentValues['FORM_ORDER'],
        'N',
        $by = null,
        $asc = null,
        array('ACTIVE' => 'Y'),
        $filtered = false
    );

    while ($arFormField = $rsFormFields->GetNext()) {
        $rsFormAnswers = CFormAnswer::GetList(
            $arFormField['ID'],
            $sort = '',
            $order = '',
            array(),
            $filtered = false
        );

        while ($arFormAnswer = $rsFormAnswers->GetNext()) {
            $sType = $arFormAnswer['FIELD_TYPE'];

            if (empty($sType))
                continue;

            $sId = 'form_' . $sType . '_' . $arFormAnswer['ID'];
            $arFormFields[$sId] = '[' . $arFormAnswer['ID'] . '] ' . $arFormField['TITLE'];
        }
    }
}