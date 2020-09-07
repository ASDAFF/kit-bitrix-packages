<? include('.begin.php') ?>
<?

use intec\core\base\Collection;
use intec\core\helpers\FileHelper;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var Closure($code, $type, $file, $fields = []) $import
 * @var CWizardStep $this
 */

$macros = $data->get('macros');
$path = FileHelper::normalizePath(WIZARD_SITE_PATH, '/').'/';

if (FileHelper::isDirectory($path)) {
    CWizardUtil::ReplaceMacrosRecursive($path, $macros);
    CWizardUtil::ReplaceMacros($path.'_index.php', $macros);
    CWizardUtil::ReplaceMacros($path.'.section.php', $macros);
}

/** CUSTOM START */
/** CUSTOM END */

?>
<? include('.end.php') ?>