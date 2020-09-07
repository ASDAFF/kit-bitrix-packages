<? include(__DIR__.'/.begin.php') ?>
<?

use intec\core\base\Collection;

/**
 * @var Collection $data
 * @var array $languages
 * @var string $solution
 * @var CWizardBase $wizard
 * @var Closure($code, $type, $file, $fields = []) $import
 * @var CWizardStep $this
 */

$code = $solution.'_advantages_'.WIZARD_SITE_ID;
$type = 'content';
$iBlock = $import($code, $type, 'content.advantages');

if (!empty($iBlock)) {
    $macros = $data->get('macros');
    $macros['CONTENT_ADVANTAGES_IBLOCK_TYPE'] = $type;
    $macros['CONTENT_ADVANTAGES_IBLOCK_ID'] = $iBlock['ID'];
    $macros['CONTENT_ADVANTAGES_IBLOCK_CODE'] = $iBlock['CODE'];
    $data->set('macros', $macros);
}

?>
<? include(__DIR__.'/.end.php') ?>