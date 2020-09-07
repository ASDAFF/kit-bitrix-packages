<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<? include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/install/wizard_sol/wizard.php') ?>
<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\StringHelper;

Loc::loadMessages(__FILE__);

class BeginStep extends CWizardStep
{
    public static function GetId() { return 'Begin'; }

    public static function GetDependencies() {
        return [
            'intec.core',
            'intec.universe'
        ];
    }

    function InitStep()
    {
        parent::InitStep();

        $this->SetStepID(static::GetId());
        $this->SetTitle(Loc::getMessage('wizard.steps.begin.title'));
        $this->content .= Loc::getMessage('wizard.steps.begin.description');
        $this->SetNextStep(SiteStep::GetId());

        $wizard = $this->GetWizard();
    }

    function ShowStep()
    {
        $next = true;
        $dependencies = static::GetDependencies();

        if (!Loader::includeModule('intec.constructor') && !Loader::includeModule('intec.constructorlite')) {
            $this->content = Loc::getMessage('wizard.steps.begin.noModule', [
                '#MODULE_ID#' => 'intec.constructor'
            ]);

            $next = false;
        }

        if ($next)
            foreach ($dependencies as $dependency) {
                if (!Loader::includeModule($dependency)) {
                    $this->content = Loc::getMessage('wizard.steps.begin.noModule', [
                        '#MODULE_ID#' => $dependency
                    ]);

                    $next = false;
                }
            }

        if (!$next)
            $this->SetNextStep(null);
    }
}

class SiteStep extends CSelectSiteWizardStep
{
    public static function GetId() { return 'Site'; }

    function InitStep()
    {
        parent::InitStep();

        $this->SetStepID(static::GetId());
        $this->SetPrevStep(BeginStep::GetId());
        $this->SetNextStep(TemplateStep::GetId());
    }
}

class TemplateStep extends CSelectTemplateWizardStep
{
    public static function GetId() { return 'Template'; }

    function InitStep()
    {
        parent::InitStep();

        $this->SetStepID(static::GetId());
        $this->SetPrevStep(SiteStep::GetId());
        $this->SetNextStep(ModeStep::GetId());
    }
}

class ModeStep extends CWizardStep
{
    public static function GetId() { return 'Mode'; }

    function InitStep()
    {
        parent::InitStep();

        $this->SetStepID(static::GetId());
        $this->SetPrevStep(TemplateStep::GetId());

        $this->SetTitle(Loc::getMessage('wizard.steps.mode.title'));

        $wizard = $this->GetWizard();
        $wizard->SetDefaultVars([
            'systemReplaceTemplate' => 'N',
            'systemConfigureRegions' => 'Y',
            'systemImportIBlocks' => 'Y'
        ]);
    }

    function ShowStep()
    {
        parent::ShowStep();

        $wizard = $this->GetWizard();

        $this->content .= '<style type="text/css">
            .panel {
                display: block;
                overflow: hidden;
            }
            .panel .panel-wrapper {
                display: block;
                margin: -10px;
            }
            .panel .panel-button {
                display: block;
                width: 50%;
                padding: 10px;
                float: left;
                border: none;
                background: none;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            .panel .panel-button .panel-button-wrapper {
                display: block;
                height: 250px;
                cursor: pointer;
                font-size: 0;
                border-radius: 3px;
                border: 2px dashed #e1e1e1;
                background: #f7f7f7;
                color: #828282;
                -webkit-transition: 0.3s;
                -moz-transition: 0.3s;
                -ms-transition: 0.3s;
                -o-transition: 0.3s;
                transition: 0.3s;
                -webkit-transition-property: background, border;
                -moz-transition-property: background, border;
                -ms-transition-property: background, border;
                -o-transition-property: background, border;
                transition-property: background, border;
            }
            .panel .panel-button .panel-button-wrapper:hover,
            .panel .panel-button .panel-button-wrapper:focus {
                border-color: #c1c1c1;
                background: #f1f1f1;
                color: #424242;
            }
            .panel .panel-button .panel-button-aligner {
                display: inline-block;
                vertical-align: middle;
                height: 100%;
                width: 0;
                overflow: hidden;
            }
            .panel .panel-button .panel-button-text {
                display: inline-block;
                vertical-align: middle;
                color: inherit;
                font-size: 24px;
            }
        </style>';

        $this->content .= '<div class="panel">';
        $this->content .= '<div class="panel-wrapper">';
        $this->content .= '<button class="panel-button" name="'.$wizard->GetVarPrefix().'systemMode" type="submit" value="Install">
            <div class="panel-button-wrapper">
                <div class="panel-button-aligner"></div>
                <div class="panel-button-text">
                    '.Loc::getMessage('wizard.modes.install').'
                </div>
            </div>
        </button>';
        $this->content .= '<button class="panel-button" name="'.$wizard->GetVarPrefix().'systemMode" type="submit" value="Update">
            <div class="panel-button-wrapper">
                <div class="panel-button-aligner"></div>
                <div class="panel-button-text">
                    '.Loc::getMessage('wizard.modes.update').'
                </div>
            </div>
        </button>';
        $this->content .= '<div style="clear: both;"></div>';
        $this->content .= '</div>';
        $this->content .= '</div>';

        if (Loader::includeModule('intec.constructor')) {
            $this->content .= '
            <div style="margin-top: 20px;">
                ' . $this->ShowHiddenField('systemReplaceTemplate', 'N') . '
                ' . $this->ShowCheckboxField('systemReplaceTemplate', 'Y', [
                    'id' => 'systemReplaceTemplate'
                ]) . '
                <label for="systemReplaceTemplate" class="wizard-input-title">
                    ' . Loc::getMessage('wizard.fields.systemReplaceTemplate') . '
                </label>
            </div>';
        }

        if (Loader::includeModule('intec.regionality')) {
            $this->content .= '
            <div style="margin-top: 20px;">
                ' . $this->ShowHiddenField('systemConfigureRegions', 'N') . '
                ' . $this->ShowCheckboxField('systemConfigureRegions', 'Y', [
                    'id' => 'systemConfigureRegions'
                ]) . '
                <label for="systemConfigureRegions" class="wizard-input-title">
                    ' . Loc::getMessage('wizard.fields.systemConfigureRegions') . '
                </label>
            </div>';
        }

        $this->content .= '
            <div style="margin-top: 20px;">
                ' . $this->ShowHiddenField('systemImportIBlocks', 'N') . '
                ' . $this->ShowCheckboxField('systemImportIBlocks', 'Y', [
                'id' => 'systemImportIBlocks'
            ]) . '
                <label for="systemImportIBlocks" class="wizard-input-title">
                    ' . Loc::getMessage('wizard.fields.systemImportIBlocks') . '
                </label>
            </div>';
    }

    function OnPostForm()
    {
        parent::OnPostForm();

        $wizard = $this->GetWizard();

        if ($wizard->IsPrevButtonClick())
            return;

        $wizard->SetCurrentStep(static::GetId());
        $mode = $wizard->GetVar('systemMode');

        if (!empty($mode))
            if ($mode == 'Update') {
                $wizard->SetCurrentStep(InstallStep::GetId());
            } else {
                $wizard->SetCurrentStep(DataSiteStep::GetId());
            }
    }
}

class DataSiteStep extends CWizardStep
{
    public static function GetId() { return 'DataSite'; }

    function InitStep()
    {
        parent::InitStep();

        $this->SetStepID(static::GetId());
        $this->SetPrevStep(ModeStep::GetId());
        $this->SetNextStep(InstallStep::GetId());

        if (Loader::includeModule('sale'))
            $this->SetNextStep(DataShopStep::GetId());

        $this->SetTitle(Loc::getMessage('wizard.steps.dataSite.title'));

        $wizard = $this->GetWizard();
        $wizard->SetDefaultVars([
            'siteName' => Loc::getMessage('wizard.fields.siteName.value'),
            'sitePhone' => Loc::getMessage('wizard.fields.sitePhone.value'),
            'siteAddress' => Loc::getMessage('wizard.fields.siteAddress.value'),
            'siteMail' => Loc::getMessage('wizard.fields.siteMail.value'),
            'siteMetaDescription' => Loc::getMessage('wizard.fields.siteMetaDescription.value'),
            'siteMetaKeywords' => Loc::getMessage('wizard.fields.siteMetaKeywords.value'),
            'shopLocation' => Loc::getMessage('wizard.fields.shopLocation.value')
        ]);
    }

    function ShowStep()
    {
        parent::ShowStep();

        $this->content .= '<div class="wizard-input-form">';
        $this->content .= '
		<div class="wizard-input-form-block">
		    <div>
			    <label for="siteName" class="wizard-input-title">
			        '.Loc::getMessage('wizard.fields.siteName').'
                </label>
			</div>
			'.$this->ShowInputField('text', 'siteName', [
			    'id' => 'siteName',
                'class' => 'wizard-field'
            ]).'
		</div>';
        $this->content .= '
		<div class="wizard-input-form-block">
		    <div>
			    <label for="sitePhone" class="wizard-input-title">
			        '.Loc::getMessage('wizard.fields.sitePhone').'
                </label>
            </div>
			'.$this->ShowInputField('text', 'sitePhone', [
                'id' => 'sitePhone',
                'class' => 'wizard-field'
            ]).'
		</div>';
        $this->content .= '
		<div class="wizard-input-form-block">
		    <div>
			    <label for="siteAddress" class="wizard-input-title">
			        '.Loc::getMessage('wizard.fields.siteAddress').'
                </label>
            </div>
			'.$this->ShowInputField('text', 'siteAddress', [
                'id' => 'siteAddress',
                'class' => 'wizard-field'
            ]).'
		</div>';
        $this->content .= '
		<div class="wizard-input-form-block">
		    <div>
			    <label for="siteMail" class="wizard-input-title">
			        '.Loc::getMessage('wizard.fields.siteMail').'
                </label>
            </div>
			'.$this->ShowInputField('text', 'siteMail', [
                'id' => 'siteMail',
                'class' => 'wizard-field'
            ]).'
		</div>';
        $this->content .= '
		<div class="wizard-input-form-block">
		    <div>
			    <label for="siteMetaDescription" class="wizard-input-title">
			        '.Loc::getMessage('wizard.fields.siteMetaDescription').'
                </label>
            </div>
			'.$this->ShowInputField('text', 'siteMetaDescription', [
                'id' => 'siteMetaDescription',
                'class' => 'wizard-field'
            ]).'
		</div>';
        $this->content .= '
		<div class="wizard-input-form-block">
		    <div>
			    <label for="siteMetaKeywords" class="wizard-input-title">
			        '.Loc::getMessage('wizard.fields.siteMetaKeywords').'
                </label>
            </div>
			'.$this->ShowInputField('text', 'siteMetaKeywords', [
                'id' => 'siteMetaKeywords',
                'class' => 'wizard-field'
            ]).'
		</div>';
        $this->content .= '</div>';
    }

    function OnPostForm()
    {
        parent::OnPostForm();

        $wizard = $this->GetWizard();

        if ($wizard->IsPrevButtonClick())
            return;

        $errors = [];
        $variables = [
            'siteName',
            'sitePhone',
            'siteAddress'
        ];

        foreach ($variables as $variable) {
            $value = $wizard->GetVar($variable);

            if (empty($value))
                $errors[] = Loc::getMessage('wizard.fields.errors.empty', [
                    '#NAME#' => Loc::getMessage('wizard.fields.'.$variable)
                ]);
        }

        if (!empty($errors))
            $this->SetError(implode('<br />', $errors));
    }
}

class DataShopStep extends CWizardStep
{
    public static function GetId() { return 'DataShop'; }

    function InitStep()
    {
        parent::InitStep();

        $this->SetStepID(static::GetId());
        $this->SetPrevStep(DataSiteStep::GetId());
        $this->SetNextStep(PersonTypesStep::GetId());

        $this->SetTitle(Loc::getMessage('wizard.steps.dataShop.title'));

        $wizard = $this->GetWizard();
        $wizard->SetDefaultVars([
            'shopLocation' => Loc::getMessage('wizard.fields.shopLocation.value')
        ]);
    }

    function ShowStep()
    {
        parent::ShowStep();

        $this->content .= '<div class="wizard-input-form">';
        $this->content .= '
		<div class="wizard-input-form-block">
		    <div>
			    <label for="shopLocation" class="wizard-input-title">
			        '.Loc::getMessage('wizard.fields.shopLocation').'
                </label>
            </div>
			'.$this->ShowInputField('text', 'shopLocation', [
                'id' => 'shopLocation',
                'class' => 'wizard-field'
            ]).'
		</div>';
        $this->content .= '</div>';
    }
}

class PersonTypesStep extends CWizardStep
{
    public static function GetId() { return 'PersonTypes'; }

    function InitStep()
    {
        parent::InitStep();

        $this->SetStepID(static::GetId());
        $this->SetPrevStep(DataShopStep::GetId());
        $this->SetNextStep(LocationsStep::GetId());

        $this->SetTitle(Loc::getMessage('wizard.steps.personTypes.title'));
    }

    function ShowStep()
    {
        parent::ShowStep();

        $this->content .= '<div class="wizard-input-form">';
        $this->content .= '<div class="wizard-input-form-block">';
        $this->content .= '<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
        $this->content .=
        '<div class="wizard-catalog-form-item">
            '.$this->ShowCheckboxField('personType[fiz]', 'Y', [
                'id' => 'personTypeF'
            ]).'<label for="personTypeF">'.Loc::getMessage('wizard.persons.fiz').'</label>
        </div>';
        $this->content .=
        '<div class="wizard-catalog-form-item">
            '.$this->ShowCheckboxField('personType[ur]', 'Y', [
                'id' => 'personTypeU'
            ]).'<label for="personTypeU">'.Loc::getMessage('wizard.persons.ur').'</label>
        </div>';
        $this->content .= '</div>';
        $this->content .=
        '<div class="wizard-catalog-form-item">
            '.Loc::getMessage('wizard.steps.locations.description')
        .'<div>';
        $this->content .= '</div>';
        $this->content .= '</div>';
    }
}

class LocationsStep extends CWizardStep
{
    public static function GetId() { return 'Locations'; }

    function InitStep()
    {
        parent::InitStep();

        $this->SetStepID(static::GetId());
        $this->SetPrevStep(PersonTypesStep::GetId());
        $this->SetNextStep(InstallStep::GetId());

        $this->SetTitle(Loc::getMessage('wizard.steps.locations.title'));
    }

    function ShowStep()
    {
        parent::ShowStep();

        $this->content .= '<div class="wizard-input-form">';
        $this->content .=
        '<div class="wizard-catalog-form-item">
            '.$this->ShowRadioField('locations', 'loc_ussr.csv', array(
                'id' => 'locationUssr',
                'checked' => 'checked'
            )).'<label for="locationUssr">'.Loc::getMessage('wizard.locations.ussr').'</label>
        </div>';
        $this->content .=
        '<div class="wizard-catalog-form-item">
            '.$this->ShowRadioField('locations', 'loc_ua.csv', array(
                'id' => 'locationUa'
            )).'<label for="locationUa">'.Loc::getMessage('wizard.locations.ua').'</label>
        </div>';
        $this->content .=
        '<div class="wizard-catalog-form-item">
            '.$this->ShowRadioField('locations', 'loc_kz.csv', array(
                'id' => 'locationKz'
            )).'<label for="locationKz">'.Loc::getMessage('wizard.locations.kz').'</label>
        </div>';
        $this->content .=
        '<div class="wizard-catalog-form-item">
            '.$this->ShowRadioField('locations', 'loc_usa.csv', array(
                'id' => 'locationUsa'
            )).'<label for="locationUsa">'.Loc::getMessage('wizard.locations.usa').'</label>
        </div>';
        $this->content .=
        '<div class="wizard-catalog-form-item">
            '.$this->ShowRadioField('locations', 'loc_cntr.csv', array(
                'id' => 'locationCntr'
            )).'<label for="locationCntr">'.Loc::getMessage('wizard.locations.country').'</label>
        </div>';
        $this->content .=
        '<div class="wizard-catalog-form-item">
            '.$this->ShowRadioField('locations', '', array(
                'id' => 'locationNone'
            )).'<label for="locationNone">'.Loc::getMessage('wizard.locations.none').'</label>
        </div>';
        $this->content .= '</div>';
    }
}

class InstallStep extends CDataInstallWizardStep
{
    public static function GetId() { return 'Install'; }

    function InitStep()
    {
        parent::InitStep();

        $this->SetStepID(static::GetId());
    }
}

class FinishStep extends CFinishWizardStep
{
    public static function GetId() { return 'End'; }

    function InitStep()
    {
        parent::InitStep();
    }

    function ShowStep()
    {
        parent::ShowStep();

        $wizard = $this->GetWizard();
        $sSiteID = WizardServices::GetCurrentSiteID($wizard->GetVar('siteID'));
        $sSiteDir = '/';
        $arSite = CSite::GetByID($sSiteID);
        $arSite = $arSite->GetNext();

        if (!empty($arSite))
            $sSiteDir = $arSite['DIR'];

        if ($wizard->GetVar('systemMode') !== 'Update')
            $this->CreateNewIndex();

        COption::SetOptionString("main", "wizard_solution", $wizard->solutionName, false, $sSiteID);

        $path = $_SERVER['DOCUMENT_ROOT'].$sSiteDir.'.wizard.json';

        if (is_file($path))
            unlink($path);
    }
}