<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?

/**
 * @const string WIZARD_SITE_ID
 * @const string WIZARD_SITE_DIR
 * @const string WIZARD_SITE_ROOT_PATH
 * @const string WIZARD_SITE_PATH
 * @const string WIZARD_RELATIVE_PATH
 * @const string WIZARD_ABSOLUTE_PATH
 * @const string WIZARD_TEMPLATE_ID
 * @const string WIZARD_TEMPLATE_RELATIVE_PATH
 * @const string WIZARD_TEMPLATE_ABSOLUTE_PATH
 * @var CWizardStep $this
 */

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\base\Collection;
use intec\core\base\InvalidParamException;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Json;
use intec\core\helpers\Type;

ini_set("max_execution_time", 0);

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('intec.core'))
    die(Loc::getMessage('wizard.services.errors.install.core'));

if (!Loader::includeModule('intec.constructor') && !Loader::includeModule('intec.constructorlite'))
    die(Loc::getMessage('wizard.services.errors.install.constructor'));

define('WIZARD_MODE_INSTALL', 'Install');
define('WIZARD_MODE_UPDATE', 'Update');

$wizard = $this->GetWizard();
$mode = $wizard->GetVar('systemMode');
$mode = ArrayHelper::fromRange([WIZARD_MODE_INSTALL, WIZARD_MODE_UPDATE], $mode);
$data = FileHelper::getFileData(WIZARD_SITE_PATH.'/.wizard.json');

try {
    $data = Json::decode($data);
} catch (InvalidParamException $exception) {}

if (Type::isArray($data))
    $data = ArrayHelper::convertEncoding(
        $data,
        Encoding::getDefault(),
        Encoding::UTF8
    );

$data = new Collection($data);
$solution = 'universe';

$languages = [];
$result = (new CLanguage())->GetList($by = 'def', $order = 'desc');

while ($language = $result->Fetch())
    $languages[$language['LID']] = $language;

unset($by);
unset($order);
unset($result);
unset($language);

if (!$data->exists('macros'))
    $data->set('macros', include(__DIR__.'/.macros.php'));