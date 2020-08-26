<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
use Bitrix\Main\Config\Option;

$module = 'kit.origami';

$catalogSubscribe = $wizard->GetVar("catalogSubscribe");
$curSiteSubscribe = ($catalogSubscribe == "Y") ? array("use" => "Y", "del_after" => "100") : array("del_after" => "100");
$subscribe = COption::GetOptionString("sale", "subscribe_prod", "");
$arSubscribe = unserialize($subscribe);
$arSubscribe[WIZARD_SITE_ID] = $curSiteSubscribe;
COption::SetOptionString("sale", "subscribe_prod", serialize($arSubscribe));

$useStoreControl = $wizard->GetVar("useStoreControl");
$useStoreControl = ($useStoreControl == "Y") ? "Y" : "N";
$curUseStoreControl = COption::GetOptionString("catalog", "default_use_store_control", "N");
COption::SetOptionString("catalog", "default_use_store_control", $useStoreControl);

$productReserveCondition = $wizard->GetVar("productReserveCondition");
$productReserveCondition = (in_array($productReserveCondition, array("O", "P", "D", "S"))) ? $productReserveCondition : "P";
COption::SetOptionString("sale", "product_reserve_condition", $productReserveCondition);

if (CModule::IncludeModule("catalog"))
{
	if($useStoreControl == "Y" && $curUseStoreControl == "N")
	{
		$dbStores = CCatalogStore::GetList(array(), array("ACTIVE" => 'Y'));
		if(!$dbStores->Fetch())
		{
			$storeImageId = 0;
			$storeImage = CFile::MakeFileArray(WIZARD_SERVICE_RELATIVE_PATH.'/images/storepoint.jpg');
			if (!empty($storeImage) && is_array($storeImage))
			{
				$storeImage['MODULE_ID'] = 'catalog';
				$storeImageId =  CFile::SaveFile($storeImage, 'catalog');
			}

			$arStoreFields = array(
				"TITLE" => GetMessage("CAT_STORE_NAME"),
				"ADDRESS" => GetMessage("STORE_ADR_1"),
				"DESCRIPTION" => GetMessage("STORE_DESCR_1"),
				"GPS_N" => GetMessage("STORE_GPS_N_1"),
				"GPS_S" => GetMessage("STORE_GPS_S_1"),
				"PHONE" => GetMessage("STORE_PHONE_1"),
				"SCHEDULE" => GetMessage("STORE_PHONE_SCHEDULE"),
				"IMAGE_ID" => $storeImageId
			);
			$newStoreId = CCatalogStore::Add($arStoreFields);

			if($newStoreId)
			{
                $oUserTypeEntity = new \CUserTypeEntity();
                global $USER_FIELD_MANAGER;
                $aUserFields    = array(
                    'ENTITY_ID'         => 'CAT_STORE',
                    'FIELD_NAME'        => 'UF_WORKTIME',
                    'USER_TYPE_ID'      => 'string',
                    'XML_ID'            => 'UF_WORKTIME',
                    'SORT'              => 500,
                    'MULTIPLE'          => 'Y',
                    'MANDATORY'         => 'N',
                    'SHOW_FILTER'       => 'N',
                    'SHOW_IN_LIST'      => '',
                    'EDIT_IN_LIST'      => '',
                    'IS_SEARCHABLE'     => 'N',
                    'EDIT_FORM_LABEL'   => array(
                        'ru'    => GetMessage("CAT_STORE_UF_WORKTIME"),
                    ),
                    'LIST_COLUMN_LABEL' => array(
                        'ru'    => GetMessage("CAT_STORE_UF_WORKTIME"),
                    ),
                    'LIST_FILTER_LABEL' => array(
                        'ru'    => GetMessage("CAT_STORE_UF_WORKTIME"),
                    ),
                    'ERROR_MESSAGE'     => array(
                        'ru'    => GetMessage("CAT_STORE_UF_WORKTIME"),
                    ),
                    'HELP_MESSAGE'      => array(
                        'ru'    => GetMessage("CAT_STORE_UF_WORKTIME"),
                    ),
                );
                $iUserFieldId   = $oUserTypeEntity->Add( $aUserFields );
                $USER_FIELD_MANAGER->Update( 'CAT_STORE', $newStoreId, array(
                    'UF_WORKTIME'  => [GetMessage("CAT_STORE_UF_WORKTIME1"),GetMessage("CAT_STORE_UF_WORKTIME2")]
                ) );

                $aUserFields    = array(
                    'ENTITY_ID'         => 'CAT_STORE',
                    'FIELD_NAME'        => 'UF_PHONE',
                    'USER_TYPE_ID'      => 'string',
                    'XML_ID'            => 'UF_PHONE',
                    'SORT'              => 500,
                    'MULTIPLE'          => 'Y',
                    'MANDATORY'         => 'N',
                    'SHOW_FILTER'       => 'N',
                    'SHOW_IN_LIST'      => '',
                    'EDIT_IN_LIST'      => '',
                    'IS_SEARCHABLE'     => 'N',
                    'EDIT_FORM_LABEL'   => array(
                        'ru'    => GetMessage("CAT_STORE_UF_PHONE"),
                    ),
                    'LIST_COLUMN_LABEL' => array(
                        'ru'    => GetMessage("CAT_STORE_UF_PHONE"),
                    ),
                    'LIST_FILTER_LABEL' => array(
                        'ru'    => GetMessage("CAT_STORE_UF_PHONE"),
                    ),
                    'ERROR_MESSAGE'     => array(
                        'ru'    => GetMessage("CAT_STORE_UF_PHONE"),
                    ),
                    'HELP_MESSAGE'      => array(
                        'ru'    => GetMessage("CAT_STORE_UF_PHONE"),
                    ),
                );
                $iUserFieldId   = $oUserTypeEntity->Add( $aUserFields );
                $USER_FIELD_MANAGER->Update( 'CAT_STORE', $newStoreId, array(
                    'UF_PHONE'  => ['+74951111111','+7495222222']
                ) );
                $aUserFields    = array(
                    'ENTITY_ID'         => 'CAT_STORE',
                    'FIELD_NAME'        => 'UF_EMAIL',
                    'USER_TYPE_ID'      => 'string',
                    'XML_ID'            => 'UF_EMAIL',
                    'SORT'              => 500,
                    'MULTIPLE'          => 'Y',
                    'MANDATORY'         => 'N',
                    'SHOW_FILTER'       => 'N',
                    'SHOW_IN_LIST'      => '',
                    'EDIT_IN_LIST'      => '',
                    'IS_SEARCHABLE'     => 'N',
                    'EDIT_FORM_LABEL'   => array(
                        'ru'    => GetMessage("CAT_STORE_UF_EMAIL"),
                    ),
                    'LIST_COLUMN_LABEL' => array(
                        'ru'    => GetMessage("CAT_STORE_UF_EMAIL"),
                    ),
                    'LIST_FILTER_LABEL' => array(
                        'ru'    => GetMessage("CAT_STORE_UF_EMAIL"),
                    ),
                    'ERROR_MESSAGE'     => array(
                        'ru'    => GetMessage("CAT_STORE_UF_EMAIL"),
                    ),
                    'HELP_MESSAGE'      => array(
                        'ru'    => GetMessage("CAT_STORE_UF_EMAIL"),
                    ),
                );
                $iUserFieldId   = $oUserTypeEntity->Add( $aUserFields );
                $USER_FIELD_MANAGER->Update( 'CAT_STORE', $newStoreId, array(
                    'UF_EMAIL'  => ['origami@mail.ru']
                ) );
                $aUserFields    = array(
                    'ENTITY_ID'         => 'CAT_STORE',
                    'FIELD_NAME'        => 'UF_METRO',
                    'USER_TYPE_ID'      => 'string',
                    'XML_ID'            => 'UF_METRO',
                    'SORT'              => 500,
                    'MULTIPLE'          => 'N',
                    'MANDATORY'         => 'N',
                    'SHOW_FILTER'       => 'N',
                    'SHOW_IN_LIST'      => '',
                    'EDIT_IN_LIST'      => '',
                    'IS_SEARCHABLE'     => 'N',
                    'EDIT_FORM_LABEL'   => array(
                        'ru'    => GetMessage("CAT_STORE_UF_METRO"),
                    ),
                    'LIST_COLUMN_LABEL' => array(
                        'ru'    => GetMessage("CAT_STORE_UF_METRO"),
                    ),
                    'LIST_FILTER_LABEL' => array(
                        'ru'    => GetMessage("CAT_STORE_UF_METRO"),
                    ),
                    'ERROR_MESSAGE'     => array(
                        'ru'    => GetMessage("CAT_STORE_UF_METRO"),
                    ),
                    'HELP_MESSAGE'      => array(
                        'ru'    => GetMessage("CAT_STORE_UF_METRO"),
                    ),
                );
                $iUserFieldId   = $oUserTypeEntity->Add( $aUserFields );
                $USER_FIELD_MANAGER->Update( 'CAT_STORE', $newStoreId, array(
                    'UF_METRO'  => GetMessage("CAT_STORE_UF_METRO")
                ) );
				$_SESSION['NEW_STORE_ID'] = $newStoreId;
			}



		}
	}
}

if(COption::GetOptionString("kit.origami", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
	return;

COption::SetOptionString("catalog", "allow_negative_amount", "N");
COption::SetOptionString("catalog", "default_can_buy_zero", "N");
COption::SetOptionString("catalog", "default_quantity_trace", "Y");