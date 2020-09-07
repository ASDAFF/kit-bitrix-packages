<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?

use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Json;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

if ($data instanceof Collection) {
    $data = ArrayHelper::convertEncoding(
        $data->asArray(),
        Encoding::UTF8,
        Encoding::getDefault()
    );
    $data = Json::encode($data);

    FileHelper::setFileData(WIZARD_SITE_PATH.'/.wizard.json', $data);
}