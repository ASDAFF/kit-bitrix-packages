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

$code = $solution.'_video_'.WIZARD_SITE_ID;
$type = 'content';
$iBlock = $import($code, $type, 'content.video');

$macros = $data->get('macros');
$macros['CONTENT_VIDEO_VIDEO_ID'] = null;
$macros['CONTENT_VIDEO_VIDEO_IDS'] = var_export([], true);

if (!empty($iBlock)) {
    $macros['CONTENT_VIDEO_IBLOCK_TYPE'] = $type;
    $macros['CONTENT_VIDEO_IBLOCK_ID'] = $iBlock['ID'];
    $macros['CONTENT_VIDEO_IBLOCK_CODE'] = $iBlock['CODE'];
    $macros['CONTENT_VIDEO_VIDEO_ID'] = null;
    $macros['CONTENT_VIDEO_VIDEO_IDS'] = [];

    $result = CIBlockElement::GetList(['SORT' => 'ASC'], [
        'IBLOCK_ID' => $iBlock['ID'],
        'ACTIVE' => 'Y'
    ]);

    $number = 0;

    while ($video = $result->GetNext()) {
        $number++;

        if ($number > 6)
            break;

        if ($number == 1)
            $macros['CONTENT_VIDEO_VIDEO_ID'] = $video['ID'];

        $macros['CONTENT_VIDEO_VIDEO_IDS'][] = $video['ID'];
    }

    $macros['CONTENT_VIDEO_VIDEO_IDS'] = var_export($macros['VIDEO_VIDEO_IDS'], true);
}

$data->set('macros', $macros);

?>
<? include(__DIR__.'/.end.php') ?>