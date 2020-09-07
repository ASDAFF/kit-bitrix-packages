<?php

/**
 * @var intec_constructorlite $this
 */

use intec\constructor\structure\block\Elements;
use intec\constructor\structure\Widgets;
use intec\core\helpers\FileHelper;
use intec\core\io\Path;

$module = 'intec.constructor';
$directory = Path::from($this->GetInstallDirectory());

FileHelper::removeDirectory(Path::from('@bitrix/components/' . $module)->value);
FileHelper::removeDirectory(Path::from('@bitrix/resources/' . $module)->value);

$directoryFrom = $directory->add('elements');
$directoryWhere = null;
$entries = FileHelper::getDirectoryEntries($directoryFrom->value, false);

foreach ($entries as $entry)
    Elements::uninstall(
        $module,
        $entry
    );

$directoryFrom = $directory->add('widgets');
$directoryWhere = null;
$entries = FileHelper::getDirectoryEntries($directoryFrom->value, false);

foreach ($entries as $entry)
    Widgets::uninstall(
        $module,
        $entry
    );

?>