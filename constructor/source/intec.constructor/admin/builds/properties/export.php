<? require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php'); ?>
<?php
if (!CModule::IncludeModule('intec.core'))
    return;

if (!CModule::IncludeModule('intec.constructor'))
    return;

use intec\Core;
use intec\core\db\ActiveRecords;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\Json;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;
use intec\constructor\models\build\Property;

$request = Core::$app->request;
$build = $request->get('build');

/** @var Build $build */
$build = Build::findOne($build);

if (!$build)
    return;

/** @var ActiveRecords $properties */
$properties = $build
    ->getProperties(false)
    ->with(['enums'])
    ->all();

$json = [];

/** @var Property $property */
foreach ($properties as $property)
    $json[] = $property->export();

$json = ArrayHelper::convertEncoding(
    $json,
    Encoding::UTF8,
    Encoding::getDefault()
);

$json = Json::encode($json);

header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false);
header('Content-Disposition: attachment; filename=Properties.json');
header('Content-Transfer-Encoding: binary');
header("Content-Length: ".StringHelper::length($json));
echo $json;

exit();