<? include(__DIR__.'/../.begin.php') ?>
<?

use Bitrix\Main\Loader;
use intec\core\base\Collection;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

if ($mode === WIZARD_MODE_UPDATE)
    return;

$module = 'search';

if (Loader::includeModule($module)) {
    if (COption::GetOptionString($module, 'exclude_mask') == '')
        COption::SetOptionString($module, 'exclude_mask', '/bitrix/*;/404.php;/upload/*');

    $NS = ['SITE_ID' => WIZARD_SITE_ID];

    if (!isset($_SESSION['SearchFirst']))
        $NS = CSearch::ReIndexAll(false, 20, $NS);
    else
        $NS = CSearch::ReIndexAll(false, 20, $_SESSION['SearchNS']);

    if (is_array($NS)) {
        $this->repeatCurrentService = true;
        $_SESSION['SearchNS'] = $NS;
        $_SESSION['SearchFirst'] = 1;
    } else {
        unset($_SESSION['SearchNS']);
        unset($_SESSION['SearchFirst']);
    }
}

?>
<? include(__DIR__.'/../.end.php') ?>