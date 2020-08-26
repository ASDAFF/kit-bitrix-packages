<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult['FIELDS']['UF_REGION_MNGR']['VALUE'] != "") {
    $arUserFields = CUser::GetByID($arResult['FIELDS']['UF_REGION_MNGR']['VALUE'])->Fetch();

    $arResult['FIELDS']['MANAGER_FIELDS'] = array(
        'PHOTO' => CFile::GetPath($arUserFields['PERSONAL_PHOTO']),
        'NAME' => $arUserFields['NAME'] . ' ' . $arUserFields['LAST_NAME'],
        'WORK_POSITION' => $arUserFields['WORK_POSITION'],
        'WORK_PROFILE' => $arUserFields['WORK_PROFILE'],
    );

}
