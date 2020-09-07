<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arSteps = [
    'BeginStep',
    'SiteStep',
    'TemplateStep',
    'ModeStep',
    'DataSiteStep'
];

if (Loader::includeModule('sale')) {
    $arSteps[] = 'DataShopStep';
    $arSteps[] = 'PersonTypesStep';
    $arSteps[] = 'LocationsStep';
}

$arSteps[] = 'InstallStep';
$arSteps[] = 'FinishStep';

$arWizardDescription = array(
    'NAME' => Loc::getMessage('intec.universe.wizard.name'),
    'DESCRIPTION' => Loc::getMessage('intec.universe.wizard.description'),
    'VERSION' => '2.2.3',
    'START_TYPE' => 'WINDOW',
    'WIZARD_TYPE' => 'INSTALL',
    'IMAGE' => '/images/'.LANGUAGE_ID.'/solution.png',
    'PARENT' => 'wizard_sol',
    'TEMPLATES' => array(
        array('SCRIPT' => 'wizard_sol')
    ),
    'STEPS' => $arSteps
);