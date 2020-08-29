<?
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
    die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$module = 'kit.origami';
CModule::includeModule('sale');
CModule::includeModule($module);
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

$keys = [
    'UF_DETAIL_TEMPLATE',
    'UF_SHOW_ON_MAIN_PAGE',
    'UF_PHOTO_DETAIL',
    'UF_DESCR_BOTTOM',
    'UF_REGIONAL_MANAGER',
    'UF_USER_WORK_EMAIL'
];

if($keys){
    $oUserTypeEntity    = new \CUserTypeEntity();
    foreach($keys as $key){
        $field = \CUserTypeEntity::GetList( [], ['FIELD_NAME' => $key] )->Fetch();//becouse not possible insert array
        if($field['ID'] > 0){
            $oUserTypeEntity->Update(
                $field['ID'],
                [
                    'EDIT_FORM_LABEL' => ['ru' => Loc::getMessage('WZD_USERFIELD_'.$key)],
                    'LIST_COLUMN_LABEL' => ['ru' => Loc::getMessage('WZD_USERFIELD_'.$key)],
                    'LIST_FILTER_LABEL' => ['ru' => Loc::getMessage('WZD_USERFIELD_'.$key)],
                    'ERROR_MESSAGE' => ['ru' => Loc::getMessage('WZD_USERFIELD_'.$key)],
                    'HELP_MESSAGE' => ['ru' => Loc::getMessage('WZD_USERFIELD_'.$key)],
                ]
            );
        }
    }
}
?>