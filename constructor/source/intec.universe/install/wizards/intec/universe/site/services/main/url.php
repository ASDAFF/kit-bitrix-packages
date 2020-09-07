<? include(__DIR__.'/../.begin.php') ?>
<?

use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

if ($mode === WIZARD_MODE_UPDATE)
    return;

$path = FileHelper::normalizePath(WIZARD_SITE_ROOT_PATH.'/urlrewrite.php');
$arUrlRewrite = array();

if (FileHelper::isFile($path))
    include($path);

$arUrlRewriteNew = [];

/** CUSTOM START */

$arUrlRewriteNew[] = [
    'CONDITION' => '#^'.WIZARD_SITE_DIR.'catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => WIZARD_SITE_DIR.'catalog/index.php'
];

$arUrlRewriteNew[] = [
    'CONDITION' => '#^'.WIZARD_SITE_DIR.'services/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => WIZARD_SITE_DIR.'services/index.php'
];

$arUrlRewriteNew[] = [
    'CONDITION' => '#^'.WIZARD_SITE_DIR.'shares/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => WIZARD_SITE_DIR.'shares/index.php'
];

$arUrlRewriteNew[] = [
    'CONDITION' => '#^'.WIZARD_SITE_DIR.'company/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => WIZARD_SITE_DIR.'company/news/index.php'
];

$arUrlRewriteNew[] = [
    'CONDITION' => '#^'.WIZARD_SITE_DIR.'company/articles/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => WIZARD_SITE_DIR.'company/articles/index.php'
];

$arUrlRewriteNew[] = [
    'CONDITION' => '#^'.WIZARD_SITE_DIR.'blog/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => WIZARD_SITE_DIR.'blog/index.php'
];

$arUrlRewriteNew[] = [
    'CONDITION' => '#^'.WIZARD_SITE_DIR.'projects/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => WIZARD_SITE_DIR.'projects/index.php'
];

$arUrlRewriteNew[] = [
    'CONDITION' => '#^'.WIZARD_SITE_DIR.'photo/#',
    'RULE' => '',
    'ID' => 'bitrix:photo',
    'PATH' => WIZARD_SITE_DIR.'photo/index.php'
];

$arUrlRewriteNew[] = [
    'CONDITION' => '#^'.WIZARD_SITE_DIR.'help/brands/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => WIZARD_SITE_DIR.'help/brands/index.php'
];

$arUrlRewriteNew[] = [
    'CONDITION' => '#^'.WIZARD_SITE_DIR.'contacts/stores/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => WIZARD_SITE_DIR.'contacts/stores/index.php'
];

/** CUSTOM END */

foreach ($arUrlRewriteNew as $arUrl)
    if (!ArrayHelper::isIn($arUrl, $arUrlRewrite)) {
        $arUrl['SITE_ID'] = WIZARD_SITE_ID;
        CUrlRewriter::Add($arUrl);
    }

?>
<? include(__DIR__.'/../.end.php') ?>