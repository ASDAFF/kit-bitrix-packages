<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CModule::IncludeModule("sale");
if(!CModule::IncludeModule("kit.orderphone"))
{
	ShowError(GetMessage("SOP_MODULE_NOT_INSTALL"));
	return;
}

if ($this->StartResultCache())
{
    if(isset($arParams["ORDER_PROPS"]) && !empty($arParams["ORDER_PROPS"]))
    {
        $arFilter["ID"] = $arParams["ORDER_PROPS"];
        $dbOrderProps = CSaleOrderProps::GetList(
            array("SORT" => "ASC"),
            $arFilter,
            false,
            false,
            array("ID", "NAME")
        );

        while ($arOrderProps = $dbOrderProps->Fetch())
        {
            $arResult["ORDER_PROPS"][$arOrderProps["ID"]] = $arOrderProps;
        }
    }
if(isset($arParams["DELIVERY_ID"]) && $arParams["DELIVERY_ID"]>0 && isset($arParams["PAY_SYSTEM_ID"]) && $arParams["PAY_SYSTEM_ID"]>0)
    $this->IncludeComponentTemplate();
}
?>