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

$code = $solution.'_products_reviews_'.WIZARD_SITE_ID;
$type = 'catalogs';
$iBlock = $import($code, $type, 'catalogs.products.reviews');

$macros = $data->get('macros');

if (!empty($iBlock)) {
    $macros['CATALOGS_PRODUCTS_REVIEWS_IBLOCK_TYPE'] = $type;
    $macros['CATALOGS_PRODUCTS_REVIEWS_IBLOCK_ID'] = $iBlock['ID'];
    $macros['CATALOGS_PRODUCTS_REVIEWS_IBLOCK_CODE'] = $iBlock['CODE'];
}

$data->set('macros', $macros);

?>
<? include(__DIR__.'/.end.php') ?>