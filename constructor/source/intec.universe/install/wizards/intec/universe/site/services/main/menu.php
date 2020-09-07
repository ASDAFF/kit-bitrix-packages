<? include(__DIR__.'/../.begin.php') ?>
<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\base\Collection;

/**
 * @var Collection $data
 * @var string $mode
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

if ($mode === WIZARD_MODE_UPDATE)
    return;

Loader::includeModule('fileman');
Loc::loadMessages(__FILE__);

$arMenuTypes = GetMenuTypes(WIZARD_SITE_ID);
$arMenuTypes['top'] = Loc::getMessage('wizard.services.main.menu.menu.top');
$arMenuTypes['left'] = Loc::getMessage('wizard.services.main.menu.menu.left');
$arMenuTypes['bottom'] = Loc::getMessage('wizard.services.main.menu.menu.bottom');
$arMenuTypes['catalog'] = Loc::getMessage('wizard.services.main.menu.menu.catalog');
$arMenuTypes['info'] = Loc::getMessage('wizard.services.main.menu.menu.info');
SetMenuTypes($arMenuTypes, WIZARD_SITE_ID);

?>
<? include(__DIR__.'/../.end.php') ?>