<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/wizard.php");
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/install/wizard_sol/wizard.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/update_client.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/update_client_partner.php");

class SelectSiteStep extends CSelectSiteWizardStep
{
    function InitStep()
    {
        parent::InitStep();

        $wizard =& $this->GetWizard();
        $wizard->solutionName = "kit.origami";
    }

    function ShowStep() {
        $wizard =& $this->GetWizard();
        parent::ShowStep();
        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/origami.css">';
    }
}


class SelectTemplateStep extends CSelectTemplateWizardStep
{
    function InitStep()
    {
        $this->SetStepID("select_template");
        $this->SetTitle(GetMessage("SELECT_TEMPLATE_TITLE"));
        $this->SetSubTitle(GetMessage("SELECT_TEMPLATE_SUBTITLE"));
        //$this->SetPrevStep("welcome_step");
        $this->SetNextStep("site_settings");
        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));
    }

    function OnPostForm()
    {
        $wizard =& $this->GetWizard();

        $proactive = COption::GetOptionString("statistic", "DEFENCE_ON", "N");
        if ($proactive == "Y")
        {
            COption::SetOptionString("statistic", "DEFENCE_ON", "N");
            $wizard->SetVar("proactive", "Y");
        }
        else
        {
            $wizard->SetVar("proactive", "N");
        }

        if ($wizard->IsNextButtonClick())
        {
            $arTemplates = array("kit_origami");

            $templateID = $wizard->GetVar("wizTemplateID");

            if (!in_array($templateID, $arTemplates))
                $this->SetError(GetMessage("wiz_template"));

            if (in_array($templateID,  array("kit_origami")))
                $wizard->SetVar("templateID", "kit_origami");
        }
    }

    function ShowStep()
    {
        $wizard =& $this->GetWizard();
        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/origami.css">';
        $templatesPath = WizardServices::GetTemplatesPath($wizard->GetPath()."/site");
        $arTemplates = WizardServices::GetTemplates($templatesPath);

        $arTemplateOrder = array();

        if (in_array("kit_origami", array_keys($arTemplates)))
        {
            $arTemplateOrder[] = "kit_origami";
        }

        $defaultTemplateID = COption::GetOptionString("main", "wizard_template_id", "kit_origami", $wizard->GetVar("siteID"));
        if (!in_array($defaultTemplateID, array("kit_origami"))) $defaultTemplateID = "kit_origami";
        $wizard->SetDefaultVar("wizTemplateID", $defaultTemplateID);

        $arTemplateInfo = array(
            "kit_origami" => array(
                "NAME" => GetMessage("WIZ_TEMPLATE_ADAPT_HORIZONTAL"),
                "DESCRIPTION" => "",
                "PREVIEW" => $wizard->GetPath()."/site/templates/kit_origami/lang/".LANGUAGE_ID."/screen.gif",
                "SCREENSHOT" => $wizard->GetPath()."/site/templates/kit_origami/lang/".LANGUAGE_ID."/screen.gif",
            ),
        );

        //	$this->content .= "<input type='hidden' value='eshop' name='templateID' id='templateID'>";//$this->ShowInputField('hidden', 'templateID', array("id" => "templateID", "value" => "kit.origami"));

        global $SHOWIMAGEFIRST;
        $SHOWIMAGEFIRST = true;

        $this->content .= '<div class="inst-template-list-block">';
        foreach ($arTemplateOrder as $templateID)
        {
            $arTemplate = $arTemplateInfo[$templateID];

            if (!$arTemplate)
                continue;

            $this->content .= '<div class="inst-template-description">';
            $this->content .= $this->ShowRadioField("wizTemplateID", $templateID, Array("id" => $templateID, "class" => "inst-template-list-inp"));

            global $SHOWIMAGEFIRST;
            $SHOWIMAGEFIRST = true;

            if ($arTemplate["SCREENSHOT"] && $arTemplate["PREVIEW"])
                $this->content .= CFile::Show2Images($arTemplate["PREVIEW"], $arTemplate["SCREENSHOT"], 150, 150, ' class="inst-template-list-img"');
            else
                $this->content .= CFile::ShowImage($arTemplate["SCREENSHOT"], 150, 150, ' class="inst-template-list-img"', "", true);

            $this->content .= '<label for="'.$templateID.'" class="inst-template-list-label">'.$arTemplate["NAME"]."</label>";
            $this->content .= "</div>";
        }

        $this->content .= "</div>";
    }
}

class SelectThemeStep extends CSelectThemeWizardStep
{

}

class SiteSettingsStep extends CSiteSettingsWizardStep
{
    function InitStep()
    {
        $wizard =& $this->GetWizard();
        $wizard->solutionName = "kit.origami";
        parent::InitStep();

        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));
        $this->SetTitle(GetMessage("WIZ_STEP_SITE_SET"));

        $siteID = $wizard->GetVar("siteID");
        $isWizardInstalled = COption::GetOptionString("kit.origami", "wizard_installed", "N", $siteID) == "Y";

        if(COption::GetOptionString("kit.origami", "wizard_installed", "N", $siteID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
            $this->SetNextStep("data_install");
        else
        {
            $this->SetNextStep("catalog_settings");
        }

        //$templateID = $wizard->GetVar("templateID");
        //$themeID = $wizard->GetVar($templateID."_themeID");

        $wizard->SetDefaultVars(
            Array(
                "siteName" => $this->GetFileContent(WIZARD_SITE_PATH."include/company_name.php", GetMessage("WIZ_COMPANY_NAME_DEF")),
                "siteSchedule" => $this->GetFileContent(WIZARD_SITE_PATH."include/schedule.php", GetMessage("WIZ_COMPANY_SCHEDULE_DEF")),
                "siteTelephone" => COption::GetOptionString('kit.origami','TEL',GetMessage("WIZ_COMPANY_TELEPHONE_DEF")),
                "siteCopy" => COption::GetOptionString('kit.origami','COPYRIGHT',GetMessage("WIZ_COMPANY_COPY_DEF")),
                "shopEmail" => COption::GetOptionString('kit.origami','MAIL',GetMessage("WIZ_COMPANY_EMAIL_DEF")),
                "shopAdress" => COption::GetOptionString('kit.origami','ADRESS',GetMessage("WIZ_COMPANY_ADRESS_DEF")),
                "shopVk" => COption::GetOptionString('kit.origami','VK',GetMessage("WIZ_SHOP_VK_DEF")),
                "shopFb" => COption::GetOptionString('kit.origami','FACEBOOK',GetMessage("WIZ_SHOP_FACEBOOK_DEF")),
                "shopInst" => COption::GetOptionString('kit.origami','INST',GetMessage("WIZ_SHOP_INST_DEF")),
                "shopYTb" => COption::GetOptionString('kit.origami','YOUTUBE',GetMessage("WIZ_SHOP_UTB_DEF")),
                "shopOd" => COption::GetOptionString('kit.origami','OK',GetMessage("WIZ_SHOP_OD_DEF")),
                "shopTm" => COption::GetOptionString('kit.origami','TELEGA',GetMessage("WIZ_SHOP_TM_DEF")),
                "shopTw" => COption::GetOptionString('kit.origami','TW',GetMessage("WIZ_SHOP_TWITTER_DEF")),
                "shopGoogle" => COption::GetOptionString('kit.origami','GOOGLE',GetMessage("WIZ_SHOP_GOOGLE_PLUS_DEF")),
                "siteMetaDescription" => GetMessage("wiz_site_desc"),
                "siteMetaKeywords" => GetMessage("wiz_keywords"),
                "siteregion" => GetMessage("WIZ_SITEREGION"),
            )
        );

    }

    function ShowStep()
    {
        $wizard =& $this->GetWizard();

        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/origami.css">';
        $this->content .= '
			<div class="wizard-input-form-block"'.(LANGUAGE_ID != "ru" ? ' style="display:none"' : '').'>
				'.$this->ShowCheckboxField(
                "siteregion",
                "Y",
                (array("id" => "siteregion", "checked"=>"checked"))
            ).'
				<label for="installDemoData">'.GetMessage("WIZ_SITEREGION").'</label>
			</div>';


        $this->content .= '<div class="wizard-input-form">';

        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteName" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_COMPANY_NAME").'</label>
			'.$this->ShowInputField('text', 'siteName', array("id" => "siteName", "class" => "wizard-field")).'
		</div>';

        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteCopy" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_COMPANY_COPY").'</label>
			'.$this->ShowInputField('textarea', 'siteCopy', array("rows"=>"3", "id" => "siteCopy", "class" => "wizard-field")).'
		</div>';

        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteTelephone" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_COMPANY_TELEPHONE").'</label>
			'.$this->ShowInputField('text', 'siteTelephone', array("id" => "siteTelephone", "class" => "wizard-field")).'
		</div>';

        $this->content .= '
        <div class="wizard-input-form-block">
			<label for="shopEmail" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_COMPANY_EMAIL").'</label>
			'.$this->ShowInputField('text', 'shopEmail', array("id" => "shopEmail", "class" => "wizard-field")).'
		</div>';

        $this->content .= '
        <div class="wizard-input-form-block">
			<label for="shopEmail" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_COMPANY_ADRESS").'</label>
			'.$this->ShowInputField('text', 'shopAdress', array("id" => "shopAdress", "class" => "wizard-field")).'
		</div>';

        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteSchedule" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_COMPANY_SCHEDULE").'</label>
			'.$this->ShowInputField('textarea', 'siteSchedule', array("rows"=>"3", "id" => "siteSchedule", "class" => "wizard-field")).'
		</div>';

        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteSchedule" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_SHOP_VK").'</label>
			'.$this->ShowInputField('text', 'shopVk', array("id" => "shopVk", "class" => "wizard-field")).'
		</div>';

        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteSchedule" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_SHOP_FACEBOOK").'</label>
			'.$this->ShowInputField('text', 'shopFb', array("id" => "shopFb", "class" => "wizard-field")).'
		</div>';

        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteSchedule" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_SHOP_INST").'</label>
			'.$this->ShowInputField('text', 'shopInst', array("id" => "shopInst", "class" => "wizard-field")).'
		</div>';

        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteSchedule" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_SHOP_UTB").'</label>
			'.$this->ShowInputField('text', 'shopYTb', array("id" => "shopYTb", "class" => "wizard-field")).'
		</div>';

        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteSchedule" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_SHOP_OD").'</label>
			'.$this->ShowInputField('text', 'shopOd', array("id" => "shopOd", "class" => "wizard-field")).'
		</div>';

        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteSchedule" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_SHOP_TM").'</label>
			'.$this->ShowInputField('text', 'shopTm', array("id" => "shopTm", "class" => "wizard-field")).'
		</div>';

        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteSchedule" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_SHOP_TWITTER").'</label>
			'.$this->ShowInputField('text', 'shopTw', array("id" => "shopTw", "class" => "wizard-field")).'
		</div>';

        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteSchedule" class="wizard-input-title" style="width: 100%;">'.GetMessage("WIZ_SHOP_GOOGLE_PLUS").'</label>
			'.$this->ShowInputField('text', 'shopGoogle', array("id" => "shopGoogle", "class" => "wizard-field")).'
		</div>';

        //$firstStep = COption::GetOptionString("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $wizard->GetVar("siteID"), false, $wizard->GetVar("siteID"));
        //$styleMeta = 'style="display:block"';
        //if($firstStep == "Y") $styleMeta = 'style="display:none"';

        $this->content .= '
		<div  id="bx_metadata" '.$styleMeta.'>
			<div class="wizard-input-form-block">
				<div class="wizard-metadata-title">'.GetMessage("wiz_meta_data").'</div>
				<label style="width: 100%;" for="siteMetaDescription" class="wizard-input-title">'.GetMessage("wiz_meta_description").'</label>
				'.$this->ShowInputField("textarea", "siteMetaDescription", Array("id" => "siteMetaDescription", "rows"=>"3", "class" => "wizard-field")).'
			</div>';
        $this->content .= '
			<div class="wizard-input-form-block">
				<label style="width: 100%;" for="siteMetaKeywords" class="wizard-input-title">'.GetMessage("wiz_meta_keywords").'</label><br>
				'.$this->ShowInputField('text', 'siteMetaKeywords', array("id" => "siteMetaKeywords", "class" => "wizard-field")).'
			</div>
		</div>';

        $firstStep = COption::GetOptionString("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $wizard->GetVar("siteID"), false, $wizard->GetVar("siteID"));


        //install Demo data
        if($firstStep == "Y")
        {
            $this->content .= '
			<div class="wizard-input-form-block"'.(LANGUAGE_ID != "ru" ? ' style="display:none"' : '').'>
				'.$this->ShowCheckboxField(
                    "installDemoData",
                    "Y",
                    (array("id" => "installDemoData", "onClick" => "if(this.checked == true){document.getElementById('bx_metadata').style.display='block';}else{document.getElementById('bx_metadata').style.display='none';}"))
                ).'
				<label for="installDemoData">'.GetMessage("wiz_structure_data").'</label>
			</div>';
        }
        else
        {
            $this->content .= $this->ShowHiddenField("installDemoData","Y");
        }



        $this->content .= '</div>';
    }
    function OnPostForm()
    {
        $wizard =& $this->GetWizard();
        $res = $this->SaveFile("siteLogo", Array("extensions" => "gif,jpg,jpeg,png", "max_height" => 150, "max_width" => 500, "make_preview" => "Y"));
    }
}

class CatalogSettings extends CWizardStep
{
    function InitStep()
    {
        $this->SetStepID("catalog_settings");
        $this->SetTitle(GetMessage("WIZ_STEP_CT"));
        if(LANGUAGE_ID != "ru")
            $this->SetNextStep("pay_system");
        else
            $this->SetNextStep("shop_settings");
        $this->SetPrevStep("site_settings");
        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));
        $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

        $wizard =& $this->GetWizard();
        $siteID = $wizard->GetVar("siteID");

        $subscribe = COption::GetOptionString("sale", "subscribe_prod", "");
        $arSubscribe = unserialize($subscribe);

        $wizard->SetDefaultVars(
            Array(
                "catalogSubscribe" => (isset($arSubscribe[$siteID])) ? ($arSubscribe[$siteID]['use'] == "Y" ? "Y" : false) : "Y",//COption::GetOptionString("kit.origami", "catalogSubscribe", "Y", $siteID),
                "catalogView" => COption::GetOptionString("kit.origami", "catalogView", "list", $siteID),
                "useStoreControl" => COption::GetOptionString("catalog", "default_use_store_control", "Y"),
                "productReserveCondition" => COption::GetOptionString("sale", "product_reserve_condition", "P")
            )
        );
    }

    function ShowStep()
    {
        $wizard =& $this->GetWizard();
        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/origami.css">';
        $this->content .= '
			<div class="wizard-input-form-block">
				<div class="wizard-catalog-title">'.GetMessage("WIZ_CATALOG_USE_STORE_CONTROL").'</div>
				<div>
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField("useStoreControl", "Y", array("id" => "use-store-control"))
            .'<label for="use-store-control">'.GetMessage("WIZ_STORE_CONTROL").'</label>
					</div>';

        $arConditions = array(
            "O" => GetMessage("SALE_PRODUCT_RESERVE_1_ORDER"),
            "P" => GetMessage("SALE_PRODUCT_RESERVE_2_PAYMENT"),
            "D" => GetMessage("SALE_PRODUCT_RESERVE_3_DELIVERY"),
            "S" => GetMessage("SALE_PRODUCT_RESERVE_4_DEDUCTION")
        );

        $this->content .= '
			<div class="wizard-catalog-form-item">'
            .$this->ShowSelectField("productReserveCondition", $arConditions).
            '<label>'.GetMessage("SALE_PRODUCT_RESERVE_CONDITION").'</label>
			</div>';
        $this->content .= '</div>
			</div>';
    }

    function OnPostForm()
    {
        $wizard =& $this->GetWizard();

    }
}

class ShopSettings extends CWizardStep
{
    function InitStep()
    {
        $this->SetStepID("shop_settings");
        $this->SetTitle(GetMessage("WIZ_STEP_SS"));
        $this->SetNextStep("person_type");
        $this->SetPrevStep("catalog_settings");
        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));
        $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

        $wizard =& $this->GetWizard();

        $siteStamp =$wizard->GetPath()."/site/templates/minimal/images/pechat.gif";
        $siteID = $wizard->GetVar("siteID");

        $wizard->SetDefaultVars(
            Array(
                "shopLocalization" => COption::GetOptionString("kit.origami", "shopLocalization", "ru", $siteID),
                "shopEmail" => COption::GetOptionString("kit.origami", "shopEmail", "sale@".$_SERVER["SERVER_NAME"], $siteID),
                "shopOfName" => COption::GetOptionString("kit.origami", "shopOfName", GetMessage("WIZ_SHOP_OF_NAME_DEF"), $siteID),
                "shopLocation" => COption::GetOptionString("kit.origami", "shopLocation", GetMessage("WIZ_SHOP_LOCATION_DEF"), $siteID),
                //"shopZip" => 101000,
                "shopAdr" => COption::GetOptionString("kit.origami", "shopAdr", GetMessage("WIZ_SHOP_ADR_DEF"), $siteID),
                "shopINN" => COption::GetOptionString("kit.origami", "shopINN", "1234567890", $siteID),
                "shopKPP" => COption::GetOptionString("kit.origami", "shopKPP", "123456789", $siteID),
                "shopNS" => COption::GetOptionString("kit.origami", "shopNS", "0000 0000 0000 0000 0000", $siteID),
                "shopBANK" => COption::GetOptionString("kit.origami", "shopBANK", GetMessage("WIZ_SHOP_BANK_DEF"), $siteID),
                "shopBANKREKV" => COption::GetOptionString("kit.origami", "shopBANKREKV", GetMessage("WIZ_SHOP_BANKREKV_DEF"), $siteID),
                "shopKS" => COption::GetOptionString("kit.origami", "shopKS", "30101 810 4 0000 0000225", $siteID),
                "siteStamp" => COption::GetOptionString("kit.origami", "siteStamp", $siteStamp, $siteID),

                //"shopCompany_ua" => COption::GetOptionString("kit.origami", "shopCompany_ua", "", $siteID),
                "shopOfName_ua" => COption::GetOptionString("kit.origami", "shopOfName_ua", GetMessage("WIZ_SHOP_OF_NAME_DEF_UA"), $siteID),
                "shopLocation_ua" => COption::GetOptionString("kit.origami", "shopLocation_ua", GetMessage("WIZ_SHOP_LOCATION_DEF_UA"), $siteID),
                "shopAdr_ua" => COption::GetOptionString("kit.origami", "shopAdr_ua", GetMessage("WIZ_SHOP_ADR_DEF_UA"), $siteID),
                "shopEGRPU_ua" =>  COption::GetOptionString("kit.origami", "shopEGRPU_ua", "", $siteID),
                "shopINN_ua" =>  COption::GetOptionString("kit.origami", "shopINN_ua", "", $siteID),
                "shopNDS_ua" =>  COption::GetOptionString("kit.origami", "shopNDS_ua", "", $siteID),
                "shopNS_ua" =>  COption::GetOptionString("kit.origami", "shopNS_ua", "", $siteID),
                "shopBank_ua" =>  COption::GetOptionString("kit.origami", "shopBank_ua", "", $siteID),
                "shopMFO_ua" =>  COption::GetOptionString("kit.origami", "shopMFO_ua", "", $siteID),
                "shopPlace_ua" =>  COption::GetOptionString("kit.origami", "shopPlace_ua", "", $siteID),
                "shopFIO_ua" =>  COption::GetOptionString("kit.origami", "shopFIO_ua", "", $siteID),
                "shopTax_ua" =>  COption::GetOptionString("kit.origami", "shopTax_ua", "", $siteID),

                "installPriceBASE" => COption::GetOptionString("kit.origami", "installPriceBASE", "Y", $siteID),
            )
        );
    }

    function ShowStep()
    {
        $wizard =& $this->GetWizard();
        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/origami.css">';
        $siteStamp = $wizard->GetVar("siteStamp", true);
        $firstStep = COption::GetOptionString("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $wizard->GetVar("siteID"), false, $wizard->GetVar("siteID"));
        if (!CModule::IncludeModule("catalog"))
        {
            $this->content .= "<p style='color:red'>".GetMessage("WIZ_NO_MODULE_CATALOG")."</p>";
            $this->SetNextStep("shop_settings");
        }
        else
        {
            $this->content .=
                '<div class="wizard-catalog-title">'.GetMessage("WIZ_SHOP_LOCALIZATION").'</div>
				<div class="wizard-input-form-block" >'.
                $this->ShowSelectField("shopLocalization", array(
                    "ru" => GetMessage("WIZ_SHOP_LOCALIZATION_RUSSIA"),
                    "ua" => GetMessage("WIZ_SHOP_LOCALIZATION_UKRAINE"),
                    "kz" => GetMessage("WIZ_SHOP_LOCALIZATION_KAZAKHSTAN"),
                    "bl" => GetMessage("WIZ_SHOP_LOCALIZATION_BELORUSSIA")
                ), array("onchange" => "langReload()", "id" => "localization_select","class" => "wizard-field", "style"=>"padding:0 0 0 15px")).'
				</div>';

            $currentLocalization = $wizard->GetVar("shopLocalization");
            if (empty($currentLocalization))
                $currentLocalization = $wizard->GetDefaultVar("shopLocalization");

            $this->content .= '<div class="wizard-catalog-title">'.GetMessage("WIZ_STEP_SS").'</div>
				<div class="wizard-input-form">';

            $this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopEmail">'.GetMessage("WIZ_SHOP_EMAIL").'</label>
					'.$this->ShowInputField('text', 'shopEmail', array("id" => "shopEmail", "class" => "wizard-field")).'
				</div>';

            //ru
            $this->content .= '<div id="ru_bank_details" class="wizard-input-form-block" style="display:'.(($currentLocalization == "ru" || $currentLocalization == "kz" || $currentLocalization == "bl") ? 'block':'none').'">
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopOfName">'.GetMessage("WIZ_SHOP_OF_NAME").'</label>'
                .$this->ShowInputField('text', 'shopOfName', array("id" => "shopOfName", "class" => "wizard-field")).'
				</div>';

            $this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopLocation">'.GetMessage("WIZ_SHOP_LOCATION").'</label>'
                .$this->ShowInputField('text', 'shopLocation', array("id" => "shopLocation", "class" => "wizard-field")).'
				</div>';

            $this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopAdr">'.GetMessage("WIZ_SHOP_ADR").'</label>'
                .$this->ShowInputField('textarea', 'shopAdr', array("rows"=>"3", "id" => "shopAdr", "class" => "wizard-field")).'
				</div>';

            if($firstStep != "Y")
            {
                $this->content .= '
					<div class="wizard-catalog-title">'.GetMessage("WIZ_SHOP_BANK_TITLE").'</div>
					<table class="wizard-input-table">
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_INN").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopINN', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_KPP").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopKPP', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_NS").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopNS', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_BANK").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopBANK', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_BANKREKV").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopBANKREKV', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_KS").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopKS', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_STAMP").':</td>
							<td class="wizard-input-table-right">'.$this->ShowFileField("siteStamp", Array("show_file_info" => "N", "id" => "siteStamp")).'<br />'.CFile::ShowImage($siteStamp, 75, 75, "border=0 vspace=5", false, false).'</td>
						</tr>
					</table>
				</div><!--ru-->
				';
            }
            $this->content .= '<div id="ua_bank_details" class="wizard-input-form-block" style="display:'.(($currentLocalization == "ua") ? 'block':'none').'">
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopOfName_ua">'.GetMessage("WIZ_SHOP_OF_NAME").'</label>'
                .$this->ShowInputField('text', 'shopOfName_ua', array("id" => "shopOfName_ua", "class" => "wizard-field")).'
					<p style="color:grey; margin: 3px 0 7px;">'.GetMessage("WIZ_SHOP_OF_NAME_DESCR_UA").'</p>
				</div>';

            $this->content .= '<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopLocation_ua">'.GetMessage("WIZ_SHOP_LOCATION").'</label>'
                .$this->ShowInputField('text', 'shopLocation_ua', array("id" => "shopLocation_ua", "class" => "wizard-field")).'
					<p style="color:grey; margin: 3px 0 7px;">'.GetMessage("WIZ_SHOP_LOCATION_DESCR_UA").'</p>
				</div>';


            $this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopAdr_ua">'.GetMessage("WIZ_SHOP_ADR").'</label>'.
                $this->ShowInputField('textarea', 'shopAdr_ua', array("rows"=>"3", "id" => "shopAdr_ua", "class" => "wizard-field")).'
					<p style="color:grey; margin: 3px 0 7px;">'.GetMessage("WIZ_SHOP_ADR_DESCR_UA").'</p>
				</div>';

            if($firstStep != "Y")
            {
                $this->content .= '
					<div class="wizard-catalog-title">'.GetMessage("WIZ_SHOP_RECV_UA").'</div>
					<p>'.GetMessage("WIZ_SHOP_RECV_UA_DESC").'</p>
					<table class="wizard-input-table">
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_EGRPU_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopEGRPU_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_INN_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopINN_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_NDS_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopNDS_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_NS_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopNS_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_BANK_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopBank_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_MFO_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopMFO_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_PLACE_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopPlace_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_FIO_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopFIO_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_TAX_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopTax_ua', array("class" => "wizard-field")).'</td>
						</tr>
					</table>
				</div>
				';
            }

            if (CModule::IncludeModule("catalog"))
            {
                $db_res = CCatalogGroup::GetGroupsList(array("CATALOG_GROUP_ID"=>'1', "BUY"=>"Y", "GROUP_ID"=>2));
                if (!$db_res->Fetch())
                {
                    $this->content .= '
					<div class="wizard-input-form-block">
						<div class="wizard-catalog-title">'.GetMessage("WIZ_SHOP_PRICE_BASE_TITLE").'</div>
						<div class="wizard-input-form-block-content">
							'. GetMessage("WIZ_SHOP_PRICE_BASE_TEXT1") .'<br><br>
							'. $this->ShowCheckboxField("installPriceBASE", "Y",
                            (array("id" => "install-demo-data")))
                        . ' <label for="install-demo-data">'.GetMessage("WIZ_SHOP_PRICE_BASE_TEXT2").'</label><br />
											
						</div>
					</div>';
                }
            }

            $this->content .= '</div>';

            $this->content .= '
				<script>
					function langReload()
					{
						var objSel = document.getElementById("localization_select");
						var locSelected = objSel.options[objSel.selectedIndex].value;
						document.getElementById("ru_bank_details").style.display = (locSelected == "ru" || locSelected == "kz" || locSelected == "bl") ? "block" : "none";
						document.getElementById("ua_bank_details").style.display = (locSelected == "ua") ? "block" : "none";
						/*document.getElementById("kz_bank_details").style.display = (locSelected == "kz") ? "block" : "none";*/
					}
				</script>
			';
        }
    }

    function OnPostForm()
    {
        $wizard =& $this->GetWizard();
        $res = $this->SaveFile("siteStamp", Array("extensions" => "gif,jpg,jpeg,png", "max_height" => 70, "max_width" => 190, "make_preview" => "Y"));
    }

}


class PersonType extends CWizardStep
{
    function InitStep()
    {
        $this->SetStepID("person_type");
        $this->SetTitle(GetMessage("WIZ_STEP_PT"));
        $this->SetNextStep("pay_system");
        $this->SetPrevStep("shop_settings");
        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));
        $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

        $wizard =& $this->GetWizard();
        $shopLocalization = $wizard->GetVar("shopLocalization", true);
        $siteID = $wizard->GetVar("siteID");

        if ($shopLocalization == "ua")
            $wizard->SetDefaultVars(
                Array(
                    "personType" => Array(
                        "fiz" => "Y",
                        //"fiz_ua" => "Y",
                        //"ur" => "Y",
                    )
                )
            );
        else
            $wizard->SetDefaultVars(
                Array(
                    "personType" => Array(
                        "fiz" =>  COption::GetOptionString("kit.origami", "personTypeFiz", "Y", $siteID),
                        "ur" => "Y"
                    )
                )
            );
    }

    function ShowStep()
    {
        $wizard =& $this->GetWizard();
        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/origami.css">';
        $shopLocalization = $wizard->GetVar("shopLocalization", true);

        $this->content .= '<div class="wizard-input-form">';
        $this->content .= '
		<div class="wizard-input-form-block">
			<!--<div class="wizard-catalog-title">'.GetMessage("WIZ_PERSON_TYPE_TITLE").'</div>-->
			<div style="padding-top:15px">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('personType[fiz]', 'Y', (array("id" => "personTypeF"))).
            ' <label for="personTypeF">'.GetMessage("WIZ_PERSON_TYPE_FIZ").'</label><br />
					</div>
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('personType[ur]', 'Y', (array("id" => "personTypeU"))).
            ' <label for="personTypeU">'.GetMessage("WIZ_PERSON_TYPE_UR").'</label><br />
					</div>';
        if ($shopLocalization == "ua")
            $this->content .=
                '<div class="wizard-catalog-form-item">'
                .$this->ShowCheckboxField('personType[fiz_ua]', 'Y', (array("id" => "personTypeFua"))).
                ' <label for="personTypeFua">'.GetMessage("WIZ_PERSON_TYPE_FIZ_UA").'</label>
					</div>';
        $this->content .= '
				</div>
			</div>
			<div class="wizard-catalog-form-item">'.GetMessage("WIZ_PERSON_TYPE").'<div>
		</div>';
        $this->content .= '</div>';
    }

    function OnPostForm()
    {
        $wizard = &$this->GetWizard();
        $personType = $wizard->GetVar("personType");

        if (empty($personType["fiz"]) && empty($personType["ur"]))
            $this->SetError(GetMessage('WIZ_NO_PT'));
    }

}

class PaySystem extends CWizardStep
{
    function InitStep()
    {
        $this->SetStepID("pay_system");
        $this->SetTitle(GetMessage("WIZ_STEP_PS"));
        $this->SetNextStep("data_collection");
        if(LANGUAGE_ID != "ru")
            $this->SetPrevStep("site_settings");
        else
            $this->SetPrevStep("person_type");
        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));
        $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

        $wizard =& $this->GetWizard();

        if(LANGUAGE_ID == "ru")
        {
            $shopLocalization = $wizard->GetVar("shopLocalization", true);

            if ($shopLocalization == "ua")
                $wizard->SetDefaultVars(
                    Array(
                        "paysystem" => Array(
                            "cash" => "Y",
                            "oshad" => "Y",
                            "bill" => "Y",
                        ),
                        "delivery" => Array(
                            "courier" => "Y",
                            "self" => "Y",
                        )
                    )
                );
            else
                $wizard->SetDefaultVars(
                    Array(
                        "paysystem" => Array(
                            "cash" => "Y",
                            "sber" => "Y",
                            "bill" => "Y",
                            "collect" => "Y"  //cash on delivery
                        ),
                        "delivery" => Array(
                            "courier" => "Y",
                            "self" => "Y",
                            "russianpost" => "N",
                            "rus_post" => "N",
                            "ua_post" => "N",
                            "kaz_post" => "N"
                        )
                    )
                );
        }
        else
        {
            $wizard->SetDefaultVars(
                Array(
                    "paysystem" => Array(
                        "cash" => "Y",
                        "paypal" => "Y",
                    ),
                    "delivery" => Array(
                        "courier" => "Y",
                        "self" => "Y",
                        "dhl" => "Y",
                        "ups" => "Y",
                    )
                )
            );
        }
    }

    function OnPostForm()
    {
        $wizard = &$this->GetWizard();
        $paysystem = $wizard->GetVar("paysystem");

        if (
            empty($paysystem["cash"])
            && empty($paysystem["sber"])
            && empty($paysystem["bill"])
            && empty($paysystem["paypal"])
            && empty($paysystem["oshad"])
            && empty($paysystem["collect"])
        )
            $this->SetError(GetMessage('WIZ_NO_PS'));
        /*payer type
                if(LANGUAGE_ID == "ru")
                {
                    $personType = $wizard->GetVar("personType");

                    if (empty($personType["fiz"]) && empty($personType["ur"]))
                        $this->SetError(GetMessage('WIZ_NO_PT'));
                }
        ===*/
    }

    function ShowStep()
    {
        $wizard =& $this->GetWizard();
        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/origami.css">';
        $shopLocalization = $wizard->GetVar("shopLocalization", true);

        $personType = $wizard->GetVar("personType");

        $arAutoDeliveries = array();
        if (CModule::IncludeModule("sale"))
        {
            $dbRes = \Bitrix\Sale\Delivery\Services\Table::getList(array(
                'filter' => array(
                    '=CLASS_NAME' => array(
                        '\Sale\Handlers\Delivery\SpsrHandler',
                        '\Bitrix\Sale\Delivery\Services\Automatic',
                        '\Sale\Handlers\Delivery\AdditionalHandler'
                    )
                ),
                'select' => array('ID', 'CODE', 'ACTIVE', 'CLASS_NAME')
            ));

            while($dlv = $dbRes->fetch())
            {
                if($dlv['CLASS_NAME'] == '\Sale\Handlers\Delivery\SpsrHandler')
                {
                    $arAutoDeliveries['spsr'] = $dlv['ACTIVE'];
                }
				elseif($dlv['CLASS_NAME'] == '\Sale\Handlers\Delivery\AdditionalHandler' && $dlv['CONFIG']['MAIN']['SERVICE_TYPE'] == 'RUSPOST')
                {
                    $arAutoDeliveries['ruspost'] = $dlv['ACTIVE'];
                }
				elseif(!empty($dlv['CODE']))
                {
                    $arAutoDeliveries[$dlv['CODE']] = $dlv['ACTIVE'];
                }
            }
        }

        $siteID = WizardServices::GetCurrentSiteID($wizard->GetVar("siteID"));
        $this->content .= '<div class="wizard-input-form">';
        $this->content .= '
		<div class="wizard-input-form-block">
			<div class="wizard-catalog-title">'.GetMessage("WIZ_PAY_SYSTEM_TITLE").'</div>
			<div>
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('paysystem[cash]', 'Y', (array("id" => "paysystemC"))).
            ' <label for="paysystemC">'.GetMessage("WIZ_PAY_SYSTEM_C").'</label>
					</div>';

        if(LANGUAGE_ID == "ru")
        {
            if($shopLocalization == "ua" && ($personType["fiz"] == "Y" || $personType["fiz_ua"] == "Y"))
                $this->content .=
                    '<div class="wizard-catalog-form-item">'.
                    $this->ShowCheckboxField('paysystem[oshad]', 'Y', (array("id" => "paysystemO"))).
                    ' <label for="paysystemS">'.GetMessage("WIZ_PAY_SYSTEM_O").'</label>
							</div>';
            if ($shopLocalization == "ru")
            {
                if ($personType["fiz"] == "Y")
                    $this->content .=
                        '<div class="wizard-catalog-form-item">'.
                        $this->ShowCheckboxField('paysystem[sber]', 'Y', (array("id" => "paysystemS"))).
                        ' <label for="paysystemS">'.GetMessage("WIZ_PAY_SYSTEM_S").'</label>
								</div>';
                if ($personType["fiz"] == "Y" || $personType["ur"] == "Y")
                    $this->content .=
                        '<div class="wizard-catalog-form-item">'.
                        $this->ShowCheckboxField('paysystem[collect]', 'Y', (array("id" => "paysystemCOL"))).
                        ' <label for="paysystemCOL">'.GetMessage("WIZ_PAY_SYSTEM_COL").'</label>
								</div>';
            }
            if($personType["ur"] == "Y")
            {
                $this->content .=
                    '<div class="wizard-catalog-form-item">'.
                    $this->ShowCheckboxField('paysystem[bill]', 'Y', (array("id" => "paysystemB"))).
                    ' <label for="paysystemB">';
                if ($shopLocalization == "ua")
                    $this->content .= GetMessage("WIZ_PAY_SYSTEM_B_UA");
                else
                    $this->content .= GetMessage("WIZ_PAY_SYSTEM_B");
                $this->content .= '</label>
							</div>';
            }
        }
        else
        {
            $this->content .=
                '<div class="wizard-catalog-form-item">'.
                $this->ShowCheckboxField('paysystem[paypal]', 'Y', (array("id" => "paysystemP"))).
                ' <label for="paysystemP">PayPal</label>
						</div>';
        }
        $this->content .= '</div>
			</div>
			<div class="wizard-catalog-form-item">'.GetMessage("WIZ_PAY_SYSTEM").'</div>
		</div>';

        if (
            LANGUAGE_ID != "ru" ||
            LANGUAGE_ID == "ru" &&
            (
                COption::GetOptionString("kit.origami", "wizard_installed", "N", $siteID) != "Y"
                || $shopLocalization == "ru" && ($arAutoDeliveries["rus_post"] != "Y")
                || $shopLocalization == "ua" && ($arAutoDeliveries["ua_post"] != "Y")
                || $shopLocalization == "kz" && ($arAutoDeliveries["kaz_post"] != "Y")
            )
        )
        {
            $deliveryNotes = array();
            $deliveryContent = '<div class="wizard-input-form-field wizard-input-form-field-checkbox">';

            if(COption::GetOptionString("kit.origami", "wizard_installed", "N", $siteID) != "Y")
            {
                $deliveryContent .= '<div class="wizard-catalog-form-item">
					'.$this->ShowCheckboxField('delivery[courier]', 'Y', (array("id" => "deliveryC"))).
                    ' <label for="deliveryC">'.GetMessage("WIZ_DELIVERY_C").'</label>
				</div>
				<div class="wizard-catalog-form-item">
					'.$this->ShowCheckboxField('delivery[self]', 'Y', (array("id" => "deliveryS"))).
                    ' <label for="deliveryS">'.GetMessage("WIZ_DELIVERY_S").'</label>
				</div>';
            }

            if(LANGUAGE_ID == "ru")
            {
                if ($shopLocalization == "ru")
                {
                    if ($arAutoDeliveries["ruspost"] != "Y")
                    {
                        \Bitrix\Sale\Delivery\Services\Manager::getHandlersList();
                        $res = \Sale\Handlers\Delivery\AdditionalHandler::getSupportedServicesList();

                        if(!empty($res['NOTES']) && is_array($res['NOTES']))
                        {
                            $deliveryNotes = $res['NOTES'];
                        }
                        else
                        {
                            $deliveryContent .= '									
								<div class="wizard-catalog-form-item">'.
                                $this->ShowCheckboxField('delivery[ruspost]', 'Y', (array("id" => "deliveryR"))).
                                ' <label for="deliveryR">'.GetMessage("WIZ_DELIVERY_R").'</label>
								</div>';
                        }
                    }

                    if ($arAutoDeliveries["rus_post"] != "Y")
                    {
                        $deliveryContent .=
                            '<div class="wizard-catalog-form-item">'.
                            $this->ShowCheckboxField('delivery[rus_post]', 'Y', (array("id" => "deliveryR2"))).
                            ' <label for="deliveryR2">'.GetMessage("WIZ_DELIVERY_R2").'</label>
							</div>';
                    }

                    if ($arAutoDeliveries["rus_post_first"] != "Y")
                    {
                        $deliveryContent .=
                            '<div class="wizard-catalog-form-item">'.
                            $this->ShowCheckboxField('delivery[rus_post_first]', 'Y', (array("id" => "deliveryRF"))).
                            ' <label for="deliveryRF">'.GetMessage("WIZ_DELIVERY_RF").'</label>
							</div>';
                    }
                }
				elseif ($shopLocalization == "ua")
                {
                    if ($arAutoDeliveries["ua_post"] != "Y")
                        $deliveryContent .=
                            '<div class="wizard-catalog-form-item">'.
                            $this->ShowCheckboxField('delivery[ua_post]', 'Y', (array("id" => "deliveryU"))).
                            ' <label for="deliveryU">'.GetMessage("WIZ_DELIVERY_UA").'</label>
							</div>';
                }
				elseif ($shopLocalization == "kz")
                {
                    if ($arAutoDeliveries["kaz_post"] != "Y")
                        $deliveryContent .=
                            '<div class="wizard-catalog-form-item">'.
                            $this->ShowCheckboxField('delivery[kaz_post]', 'Y', (array("id" => "deliveryK"))).
                            ' <label for="deliveryK">'.GetMessage("WIZ_DELIVERY_KZ").'</label>
							</div>';
                }
            }
            else
            {
                $deliveryContent .=
                    '<div class="wizard-catalog-form-item">'.
                    $this->ShowCheckboxField('delivery[dhl]', 'Y', (array("id" => "deliveryD"))).
                    ' <label for="deliveryD">DHL</label>
					</div>';
                $deliveryContent .=
                    '<div class="wizard-catalog-form-item">'.
                    $this->ShowCheckboxField('delivery[ups]', 'Y', (array("id" => "deliveryU"))).
                    ' <label for="deliveryU">UPS</label>
					</div>
				</div>';
            }

            $this->content  .=
                '<div class="wizard-input-form-block">
					<div class="wizard-catalog-title">'.GetMessage("WIZ_DELIVERY_TITLE").'</div>
					<div>'.
                $deliveryContent.
                '</div>
					<div class="wizard-catalog-form-item">'.GetMessage("WIZ_DELIVERY").'</div>
				</div>';						;
        }

        $this->content .= '
		<div>
			<div class="wizard-catalog-title">'.GetMessage("WIZ_LOCATION_TITLE").'</div>
			<div>
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
        if(in_array(LANGUAGE_ID, array("ru", "ua")))
        {
            $this->content .=
                '<div class="wizard-catalog-form-item">'.
                $this->ShowRadioField("locations_csv", "loc_ussr.csv", array("id" => "loc_ussr", "checked" => "checked"))
                ." <label for=\"loc_ussr\">".GetMessage('WSL_STEP2_GFILE_USSR')."</label>
				</div>";
            $this->content .=
                '<div class="wizard-catalog-form-item">'.
                $this->ShowRadioField("locations_csv", "loc_ua.csv", array("id" => "loc_ua"))
                ." <label for=\"loc_ua\">".GetMessage('WSL_STEP2_GFILE_UA')."</label>
				</div>";
            $this->content .=
                '<div class="wizard-catalog-form-item">'.
                $this->ShowRadioField("locations_csv", "loc_kz.csv", array("id" => "loc_kz"))
                ." <label for=\"loc_kz\">".GetMessage('WSL_STEP2_GFILE_KZ')."</label>
				</div>";
        }
        $this->content .=
            '<div class="wizard-catalog-form-item">'.
            $this->ShowRadioField("locations_csv", "loc_usa.csv", array("id" => "loc_usa"))
            ." <label for=\"loc_usa\">".GetMessage('WSL_STEP2_GFILE_USA')."</label>
			</div>";
        $this->content .=
            '<div class="wizard-catalog-form-item">'.
            $this->ShowRadioField("locations_csv", "loc_cntr.csv", array("id" => "loc_cntr"))
            ." <label for=\"loc_cntr\">".GetMessage('WSL_STEP2_GFILE_CNTR')."</label>
			</div>";
        $this->content .=
            '<div class="wizard-catalog-form-item">'.
            $this->ShowRadioField("locations_csv", "", array("id" => "none"))
            ." <label for=\"none\">".GetMessage('WSL_STEP2_GFILE_NONE')."</label>
			</div>";

        $this->content .= '
				</div>
			</div>
		</div>';

        $this->content .= '<div class="wizard-catalog-form-item">'.GetMessage("WIZ_DELIVERY_HINT").'</div>';

        $this->content .= '</div>';
    }
}


class DataCollection extends CWizardStep
{
    function InitStep()
    {
        $this->SetStepID("data_collection");
        $this->SetTitle(GetMessage("WIZ_STEP_DC"));
        $this->SetNextStep("data_install");
        $this->SetPrevStep("pay_system");

        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));
        $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

        $wizard =& $this->GetWizard();

    }

    function OnPostForm()
    {
        $wizard = &$this->GetWizard();
        $dataCollection = $wizard->GetVar("dataCollection");

        if(!empty($dataCollection['Email']) && !empty($dataCollection['Name']) && !empty($dataCollection['Phone']))
        {
            if (filter_var($dataCollection['Email'], FILTER_VALIDATE_EMAIL))
            {
                if (preg_match("/^[0-9()+\-\s]+$/",$dataCollection['Phone']))
                {
                    if($_SERVER['SERVER_NAME'])
                    {
                        $site = $_SERVER['SERVER_NAME'];
                    }
					elseif($_SERVER['HTTP_HOST'])
                    {
                        $site = $_SERVER['HTTP_HOST'];
                    }
                    $str = '';
                    $arUpdateList = CUpdateClient::GetUpdatesList($str);
                    $request = array(
                        'ACTION' => 'ADD',
                        'SITE' => $site,
                        'KEY' => md5("BITRIX" . CUpdateClientPartner::GetLicenseKey() . "LICENCE"),
                        'LICENSE' => $arUpdateList["CLIENT"][0]["@"]["LICENSE"],
                        'MODULE' => 'kit.origami',
                        'NAME' => $dataCollection['Name'],
                        'EMAIL' => $dataCollection['Email'],
                        'PHONE' => $dataCollection['Phone'],
                        'BITRIX_DATE_FROM' => $arUpdateList["CLIENT"][0]["@"]["DATE_FROM"],
                        'BITRIX_DATE_TO' => $arUpdateList["CLIENT"][0]["@"]["DATE_TO"],
                    );
                    $options = array(
                        'http' => array(
                            'method' => 'POST',
                            'header' => "Content-Type: application/json; charset=utf-8\r\n",
                            'content' => json_encode($request)
                        )
                    );

                    $context = stream_context_create($options);
                    $answer = file_get_contents('https://www.kit.ru:443/api/datacollection/index.php', 0, $context);
                }
                else
                {
                    $this->SetError(GetMessage("DATA_COOLECTION_NOT_VALID_PHONE"));
                }
            }
            else
            {
                $this->SetError(GetMessage("DATA_COOLECTION_NOT_VALID_EMAIL"));
            }
        }
        else
        {
            $this->SetError(GetMessage("DATA_COOLECTION_NOT_REQUIRE"));
        }
    }

    function ShowStep()
    {

        $wizard =& $this->GetWizard();
        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/origami.css">';

        $dataCollection = $wizard->GetVar("data_collection");
        $this->content .= '<div class="data-collection-wrapper">';
        $this->content .= '<div class="data-collection-left">';
        $this->content .= '<div class="data-collection-left__top-text">'.GetMessage("wiz_datacollection_top_text").'</div>';
        $this->content .= '
					<div class="wizard-input-form-block">
						<label for="dataCollectionName" class="wizard-input-title">'.GetMessage("wiz_datacollection_name").'*</label><br>
						'.$this->ShowInputField('text', 'dataCollection[Name]', array("id" => "dataCollectionName", "class" => "wizard-field")).'
					</div>';
        $this->content .= '
					<div class="wizard-input-form-block">
						<label for="dataCollectionEmail" class="wizard-input-title">'.GetMessage("wiz_datacollection_email").'*</label><br>
						'.$this->ShowInputField('text', 'dataCollection[Email]', array("id" => "dataCollectionEmail", "class" => "wizard-field")).'
					</div>';
        $this->content .= '
					<div class="wizard-input-form-block">
						<label for="dataCollectionPhone" class="wizard-input-title">'.GetMessage("wiz_datacollection_phone").'*</label><br>
						'.$this->ShowInputField('text', 'dataCollection[Phone]', array("id" => "dataCollectionPhone", "class" => "wizard-field")).'
					</div>';
        $this->content .= '<div class="data-collection-left__bottom-text">'.GetMessage("wiz_datacollection_bottom_text").'</div>';
        $this->content .= '</div>';
        $this->content .= '<div class="data-collection-right">';
        $this->content .= '<div class="data-collection-right__avatar"><img class="data-collection-right__avatar-img" src="'.$wizard->GetPath().'/images/manager.png'.'"></div>';
        $this->content .= '<div class="data-collection-right__personal-manager">'.GetMessage("wiz_datacollection_personal_manager").'</div>';
        $this->content .= '<div class="data-collection-right__personal-manager-name">'.GetMessage("wiz_datacollection_personal_manager_name").'</div>';
        $this->content .= '<ul class="data-collection-right__plusses"><li>'.GetMessage("wiz_datacollection_plus1").'</li><li>'.GetMessage("wiz_datacollection_plus2").'</li><li>'.GetMessage("wiz_datacollection_plus3").'</li></ul>';
        $this->content .= '</div>';
        $this->content .= '</div>';
    }
}



class DataInstallStep extends CDataInstallWizardStep
{
    function CorrectServices(&$arServices)
    {
        if($_SESSION["BX_ESHOP_LOCATION"] == "Y")
            $this->repeatCurrentService = true;
        else
            $this->repeatCurrentService = false;

        $wizard =& $this->GetWizard();
        if($wizard->GetVar("installDemoData") != "Y")
        {
        }
    }

    function ShowStep() {
        $wizard =& $this->GetWizard();
        parent::ShowStep();
        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/origami.css">';
    }
}

class FinishStep extends CFinishWizardStep
{
    function InitStep()
    {
        $this->SetStepID("finish");
        $this->SetNextStep("finish");
        $this->SetTitle(GetMessage("FINISH_STEP_TITLE"));
        $this->SetNextCaption(GetMessage("wiz_go"));
    }

    function ShowStep()
    {

        $wizard =& $this->GetWizard();
        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/origami.css">';
        if ($wizard->GetVar("proactive") == "Y")
            COption::SetOptionString("statistic", "DEFENCE_ON", "Y");

        $siteID = WizardServices::GetCurrentSiteID($wizard->GetVar("siteID"));
        $rsSites = CSite::GetByID($siteID);
        $siteDir = "/";
        if ($arSite = $rsSites->Fetch())
            $siteDir = $arSite["DIR"];

        $wizard->SetFormActionScript(str_replace("//", "/", $siteDir."/?finish"));

        $this->CreateNewIndex();

        COption::SetOptionString("main", "wizard_solution", $wizard->solutionName, false, $siteID);

        $this->content .=
            '<table class="wizard-completion-table">
				<tr>
					<td class="wizard-completion-cell">'
            .GetMessage("FINISH_STEP_CONTENT").
            '</td>
				</tr>
			</table>';
        //	$this->content .= "<br clear=\"all\"><a href=\"/bitrix/admin/wizard_install.php?lang=".LANGUAGE_ID."&site_id=".$siteID."&wizardName=bitrix:eshop.mobile&".bitrix_sessid_get()."\" class=\"button-next\"><span id=\"next-button-caption\">".GetMessage("wizard_store_mobile")."</span></a><br>";

        if ($wizard->GetVar("installDemoData") == "Y")
            $this->content .= GetMessage("FINISH_STEP_REINDEX");

        if(IsModuleInstalled('kit.b2bshop') && (file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.b2bshop/") || file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/kit/b2bshop/"))) {
            if ((isset($_SERVER['REQUEST_SCHEME']) AND $_SERVER['REQUEST_SCHEME'] === 'https') OR (isset($_SERVER['HTTPS']) AND $_SERVER['HTTPS'] === 'on'))
                $protocol = 'https';
            else
                $protocol = 'http';


            header('Location: ' . $protocol . '://' . $_SERVER['HTTP_HOST'] . '/bitrix/admin/wizard_install.php?' . bitrix_sessid_get() . '&lang=' . LANGUAGE_ID . '&wizardName=kit:b2bcabinet');
        }

    }

}
