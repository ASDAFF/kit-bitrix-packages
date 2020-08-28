<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

//if (!defined("WIZARD_SITE_ID") || !defined("WIZARD_SITE_DIR"))
	//return;

function ___writeToAreasFile($path, $text)
{
	//if(file_exists($fn) && !is_writable($abs_path) && defined("BX_FILE_PERMISSIONS"))
	//	@chmod($abs_path, BX_FILE_PERMISSIONS);

	$fd = @fopen($path, "wb");
	if(!$fd)
		return false;

	if(false === fwrite($fd, $text))
	{
		fclose($fd);
		return false;
	}

	fclose($fd);

	if(defined("BX_FILE_PERMISSIONS"))
		@chmod($path, BX_FILE_PERMISSIONS);
}

if (COption::GetOptionString("main", "upload_dir") == "")
	COption::SetOptionString("main", "upload_dir", "upload");

if(COption::GetOptionString("sotbit.origami", "wizard_installed", "N", WIZARD_SITE_ID) == "N" || WIZARD_INSTALL_DEMO_DATA)
{
    $bitrixLocalDir = $_SERVER["DOCUMENT_ROOT"] ."/local/";

    if(file_exists(WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/local/"))
    {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/local/",
            $bitrixLocalDir,
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }

    if(file_exists(WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/"))
    {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/",
            WIZARD_SITE_PATH,
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false,
            $exclude = "local"
        );
    }

    if(file_exists(WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/upload/"))
    {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/upload/",
            $_SERVER["DOCUMENT_ROOT"].'/upload/',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }
}

COption::SetOptionString("sotbit.origami",'TEL',$wizard->GetVar("siteTelephone"));
COption::SetOptionString("sotbit.origami",'COPYRIGHT',$wizard->GetVar("siteCopy"));
COption::SetOptionString("sotbit.origami",'EMAIL',$wizard->GetVar("shopEmail"));



COption::SetOptionString("sotbit.origami",'URL_CART',WIZARD_SITE_DIR.'personal/cart/');
COption::SetOptionString("sotbit.origami",'URL_ORDER',WIZARD_SITE_DIR.'personal/order/make/');
COption::SetOptionString("sotbit.origami",'URL_PERSONAL',WIZARD_SITE_DIR.'personal/');
COption::SetOptionString("sotbit.origami",'URL_PAYMENT',WIZARD_SITE_DIR.'personal/order/payment/');
COption::SetOptionString("sotbit.origami",'URL_PAGE_ORDER',WIZARD_SITE_DIR.'personal/order/');
COption::SetOptionString("sotbit.origami",'TABLE_SIZE_URL',WIZARD_SITE_DIR.'clients/table_sizes/#table');
COption::SetOptionString("sotbit.origami", 'DETAIL_TEXT_INCLUDE', GetMessage('DETAIL_TEXT_INCLUDE', array('#SITE_DIR#' => WIZARD_SITE_DIR)));




$wizard =& $this->GetWizard();/*
___writeToAreasFile(WIZARD_SITE_PATH."include/company_name.php", $wizard->GetVar("siteName"));
___writeToAreasFile(WIZARD_SITE_PATH."include/copyright.php", $wizard->GetVar("siteCopy"));
___writeToAreasFile(WIZARD_SITE_PATH."include/schedule.php", $wizard->GetVar("siteSchedule"));
___writeToAreasFile(WIZARD_SITE_PATH."include/telephone.php", $wizard->GetVar("siteTelephone"));
*/


if(COption::GetOptionString("sotbit.origami", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
	return;

WizardServices::PatchHtaccess(WIZARD_SITE_PATH);

WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."about/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."catalog/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."include/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."login/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."news/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."personal/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."search/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."store/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."blog/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."promotions/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."brands/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."faq/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."vlog/", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."_index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".top.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."sect_search.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".sotbit_bottom1.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".sotbit_bottom2.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));

WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."about/", Array("SALE_EMAIL" => $wizard->GetVar("shopEmail")));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."about/delivery/", Array("SALE_PHONE" => $wizard->GetVar("siteTelephone")));

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.section.php", array("SITE_DESCRIPTION" => htmlspecialcharsbx($wizard->GetVar("siteMetaDescription"))));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.section.php", array("SITE_KEYWORDS" => htmlspecialcharsbx($wizard->GetVar("siteMetaKeywords"))));


$arUrlRewrite = array();
if (file_exists(WIZARD_SITE_ROOT_PATH."/urlrewrite.php"))
{
	include(WIZARD_SITE_ROOT_PATH."/urlrewrite.php");
}

$arNewUrlRewrite = array(
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."news/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:news",
		"PATH"	=>	 WIZARD_SITE_DIR."news/index.php",
	),
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."blog/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:news",
		"PATH"	=>	 WIZARD_SITE_DIR."blog/index.php",
	),
    array(
        "CONDITION"	=>	"#^".WIZARD_SITE_DIR."vlog/#",
        "RULE"	=>	"",
        "ID"	=>	"bitrix:news",
        "PATH"	=>	 WIZARD_SITE_DIR."vlog/index.php",
    ),
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."promotions/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:news",
		"PATH"	=>	 WIZARD_SITE_DIR."promotions/index.php",
	),
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."brands/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:news",
		"PATH"	=>	 WIZARD_SITE_DIR."brands/index.php",
	),
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."catalog/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:catalog",
		"PATH"	=>	 WIZARD_SITE_DIR."catalog/index.php",
	),
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."personal/order/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:sale.personal.order",
		"PATH"	=>	 WIZARD_SITE_DIR."personal/order/index.php",
	),
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."personal/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:sale.personal.section",
		"PATH"	=>	 WIZARD_SITE_DIR."personal/index.php",
	),
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."store/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:catalog.store",
		"PATH"	=>	WIZARD_SITE_DIR."store/index.php",
	),
    array(
        "CONDITION"	=>	"#^".WIZARD_SITE_DIR."services/#",
        "RULE"	=>	"",
        "ID"	=>	"bitrix:catalog",
        "PATH"	=>	WIZARD_SITE_DIR."services/index.php",
    )
);

foreach ($arNewUrlRewrite as $arUrl)
{
	if (!in_array($arUrl, $arUrlRewrite))
	{
		CUrlRewriter::Add($arUrl);
	}
}
?>
