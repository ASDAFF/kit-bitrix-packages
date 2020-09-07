<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:main.include',
    '.default',
    array(
        'AREA_FILE_SHOW' => 'file',
        'PATH' => $arResult['LOGOTYPE']['PATH'],
        'EDIT_TEMPLATE' => null
    ),
    $this->getComponent()
) ?>